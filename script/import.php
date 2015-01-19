<?
error_reporting(E_ALL);
ini_set('display_errors','1');
set_time_limit(0);
try 
{
	include("../lib/cfg_Config.php");
	include("../lib/adodb/adodb.inc.php");
	include("../lib/act_OpenDB.php");
	include("../lib/lib_CommonAdmin.php");

	$cfg_import['start_time'] = time();
	$cfg_import['import_id'] = uuid();
	$cfg_import['filename_import'] = $config['path'].'script/import.csv';
	
	$cfg_import['archive_import_file'] = false;
	$cfg_import['filename_archive'] = $config['path'].'script/archive/'.date('d.m.Y-H.i.s', $cfg_import['start_time']).'-'.$cfg_import['import_id'].'.csv';
	
	$cfg_import['log_file'] = true;
	$cfg_import['filename_log'] = $config['path'].'script/logs/'.date('d.m.Y-H.i.s', $cfg_import['start_time']).'-'.$cfg_import['import_id'].'.log';
	
	$cfg_import['header_columns'] = array(
		0 => array(
			'header' => strtolower('Groups')
			,'required' => true
			,'column' => ''
		)
		,array(
			'header' => strtolower('Category')
			,'required' => true
			,'column' => ''
		)
		,array(
			'header' => strtolower('Barcode Ref')
			,'required' => true
			,'column' => ''
		)
		,array(
			'header' => strtolower('Product Name')
			,'required' => true
			,'column' => ''
		)
		,array(
			'header' => strtolower('Cost')
			,'required' => false
			,'column' => ''
		)
		,array(
			'header' => strtolower('Selling Price')
			,'required' => false
			,'column' => ''
		)
		,array(
			'header' => strtolower('VAT £')
			,'required' => true
			,'column' => ''
		)
		,array(
			'header' => strtolower('Expr1007')
			,'required' => true
			,'column' => ''
		)
		,array(
			'header' => strtolower('Gross Margin £')
			,'required' => false
			,'column' => ''
		)
		,array(
			'header' => strtolower('Gross Margin %')
			,'required' => false
			,'column' => ''
		)
		,array(
			'header' => strtolower('Qty Stock (Current Mth)')
			,'required' => true
			,'column' => ''
		)
		,array(
			'header' => strtolower('Sales Value (Current Mth)')
			,'required' => false
			,'column' => ''
		)
		,array(
			'header' => strtolower('Stock Value (Current Mth)')
			,'required' => false
			,'column' => ''
		)
		,array(
			'header' => strtolower('Purchases Qty')
			,'required' => false
			,'column' => ''
		)
		,array(
			'header' => strtolower('Sales Qty')
			,'required' => false
			,'column' => ''
		)
		,array(
			'header' => strtolower('Gross Margin £ Total')
			,'required' => false
			,'column' => ''
		)
		,array(
			'header' => strtolower('Net Selling Price')
			,'required' => false
			,'column' => ''
		)
	);
	
	function import_log($text, $print = false)
	{
		global $cfg_import;
		$date = date('d/m/Y H:i:s');
		
		if($cfg_import['log_file'])
		{
			if ($handle = @fopen($cfg_import['filename_log'], 'a'))
			{
				fwrite($handle, "\n".'===================='.$date.'===================='."\n".$text."\n");
				fclose($handle);
			}
			else
				throw new Exception('Unable to open log file "'.$cfg_import['filename_log'].'"');
		}
		if($print)
			echo "<br />\n".'===================='.$date.'===================='."<br />\n<pre>".$text."</pre><br />\n";
	}
	
	function check_csv_structure($handle)
	{
		global $cfg_import;
		
		if(($pointer = ftell($handle)) === false)
			throw new Exception('Cannot tell import file pointer');
		if(fseek($handle, 0) == -1)
			throw new Exception('Cannot rewind import file');
		
		$error_count = 0;
		$line = 0;
		while(($data = fgetcsv($handle, 0)) !== false)
		{
			$line++;
			$data = array_map('trim', $data);
			
			if(count($data) != count($cfg_import['header_columns']))
			{
				$columns = array();
				foreach($cfg_import['header_columns'] as $column_count=>$details)
					$columns[] = $details['header'];
				import_log('The column count doesn\'t match the values count at line '.$line.':'."\nColumns:\n".var_export($columns,true)."\nValues:\n".var_export($data,true));
				$error_count++;
			}
			else
			{
				$missing = array();
				foreach($cfg_import['header_columns'] as $column_count=>$details)
					if($details['required'] && $data[$column_count] == '')
						$missing[] = $details['header'];
				if(count($missing))
				{
					import_log('Missing values at line '.$line.': '.implode(',', $missing));
					$error_count++;
				}
			}
		}
		
		if(fseek($handle, $pointer) == -1)
			throw new Exception('Cannot rewind import file');
		return ($error_count)?false:true;
	}
	
	function get_category($column1, $column2)
	{
		global $db, $category_ord;
		
		$column2 = trim($column2);
		if(strtolower($column2) == 'deleted')
			return 1;
			
		$categories = array();
		$categories[] = trim($column1);
		
		$pattern = '/^\(([^\)]*)\)(.+)/'; // (category1) category2
		preg_match($pattern, $column2, $matches);
		if(count($matches))
		{
			$categories[] = trim($matches[1]);
			$categories[] = trim($matches[2]);
		}
		else
			$categories[] = $column2;
			
		$parent_id = 1;
		$trail = array();
		$trail[] = array('name'=>'Home', 'url'=>'index.php');
		$trail[] = array('name'=>'Shop', 'id'=>'1', 'url'=>'index.php/fuseaction/shop.category/category_id/1');
		foreach($categories as $category)
		{
			$result = $db->Execute(
				$sql = sprintf("
					SELECT 
						id 
					FROM
						shop_categories
					WHERE
						name = %s
					AND
						parent_id = %u
				"
					,$db->Quote($category)
					,$parent_id
			));
			$result = $result->FetchRow();
			if(!$result)
			{
				$db->Execute(
					$sql = sprintf("
						INSERT INTO
							shop_categories
						SET
							name = %s
							,parent_id = %u
					"
						,$db->Quote($category)
						,$parent_id
						
				));
				$category_id = $db->Insert_ID();
				if($category_id)
				{
					$trail[] = array('name'=>$category, 'id'=>$category_id.'', 'url'=>'index.php/fuseaction/shop.category/category_id/'.$category_id);
					$db->Execute(
						$sql = sprintf("
							UPDATE
								shop_categories
							SET
								trail = %s
								,ord = %u
							WHERE
								id = %u
						"
							,$db->Quote(serialize($trail))
							,$category_ord[$parent_id]+1
							,$category_id
					));
					$category_ord[$parent_id]++;
				}
				$parent_id = $category_id;
			}
			else
				$parent_id = $result['id'];
				
			if(!$parent_id)
				throw new Exception('Cannot retrive category');
		}
		
		return $parent_id;
	}
	
	function insert_products(&$sql_insert, $force_insert=false)
	{
		global $db;
		
		if(count($sql_insert) >= 50 || $force_insert)
		{
			if(count($sql_insert))
				$db->Execute(
					$sql = sprintf("
						INSERT INTO
							shop_products
						(
							category_id
							,hidden
							,code
							,name
							,vat
							,price
							,stock
							,options
							,specs
							,ord
							,`inserted`
							,`updated`
						)
						VALUES
							%s
					"
					, implode(',', $sql_insert)
				));
			$sql_insert = array();
		}
	}
	
	function true_price($price)
	{
		return str_replace(',','.',$price);
	}
	
	echo "<br />\nLog file: ".$cfg_import['filename_log']."<br />\n";
	
	import_log('Start import', true);
	
	if(!file_exists($cfg_import['filename_import']))
		throw new Exception('Import file does not exist.');
		
	if(!is_readable($cfg_import['filename_import']))
		throw new Exception('Import file does not have read permission.');
		
	$archived = false;
	if($cfg_import['archive_import_file'])
	{
		if(!copy($cfg_import['filename_import'], $cfg_import['filename_archive']))
			throw new Exception('Cannot archive import file');
		$archived = true;
	}
	
    $fp = fopen($cfg_import['filename_import'], 'r');
	if(!$fp)
		throw new Exception('Cannot open import file');

	if(!flock($fp, LOCK_EX))
		throw new Exception('Cannot lock import file');

	if(!check_csv_structure($fp))
	{
		if(!$archived)
		{
			if(!copy($cfg_import['filename_import'], $cfg_import['filename_archive']))
				throw new Exception('Cannot archive import file');
		}
		throw new Exception('Invalid file structure');
	}
	
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();
	
	$category_ord = array();
	$results = $db->Execute(
		$sql = sprintf("
			SELECT
				parent_id
				,MAX(ord) AS max
			FROM
				shop_categories
			GROUP BY
				parent_id
		"
	));
	while($row = $results->FetchRow())
		$category_ord[$row['parent_id']] = $row['max'];
		
	$product_ord = array();
	$results = $db->Execute(
		$sql = sprintf("
			SELECT
				category_id
				,MAX(ord) AS max
			FROM
				shop_products
			GROUP BY
				category_id
		"
	));
	while($row = $results->FetchRow())
		$product_ord[$row['category_id']] = $row['max'];
	
	$sql_insert = array();
	
	$line = 0;
	while(($data = fgetcsv($fp, 0)) !== false)
	{
		$line++;
		if($line == 1)
			continue;
			
		$data = array_map('trim', $data);
		
		if(strtolower($data[1]) == 'deleted')
			continue;
		
		$product = $db->Execute(
			$sql = sprintf("
				SELECT 
					id 
				FROM
					shop_products
				WHERE
					code = %s
			"
			, $db->Quote($data[2])
		));
		$product = $product->FetchRow();
		if(!$product)
		{
			$category_id = get_category($data[0],$data[1]);
			$sql_insert[] = sprintf(
				"(%u,%u,%s,%s,%u,%f,%d,%s,%s,%u,NOW(),NOW())"
				,$category_id
				,(strtolower($data[1]) == 'deleted')?1:0
				,$db->Quote($data[2])
				,$db->Quote($data[3])
				,(true_price($data[6])+0 > 0)?1:0
				,true_price($data[7])
				,$data[10]
				,$db->Quote(serialize(array()))
				,$db->Quote(serialize(array(0=>array('name'=>'','value'=>''))))
				,$product_ord[$category_id]+1
			);
			$product_ord[$category_id]++;
			import_log("Creating product: \nname: ".$data[3]."\ncode: ".$data[2], true);
			insert_products($sql_insert);
		}
		else
		{
			$db->Execute(
				$sql = sprintf("
					UPDATE
						shop_products
					SET
						hidden = %u
						,name = %s
						,vat = %u
						,price = %f
						,stock = %d
						,`updated` = NOW()
					WHERE
						id = %u
				"
					,(strtolower($data[1]) == 'deleted')?1:0
					,$db->Quote($data[3])
					,(true_price($data[6])+0 > 0)?1:0
					,true_price($data[7])
					,$data[10]
					,$product['id']
			));
			import_log("Updating product: \nname: ".$data[3]."\ncode: ".$data[2], true);
		}
	}
	insert_products($sql_insert, true);
	
	$ok=$db->CompleteTrans();
	if(!$ok)
		throw new Exception("Database error:\nerror no: ".$db->ErrorNo()."\nerror msg: ".$db->ErrorMsg());
	
	import_log('Import ended succesfully', true);
}
catch (Exception $e)
{
    $msg = 'Caught exception: '."\n";
	$msg .= 'message: '.$e->getMessage()."\n";
	$msg .= 'code: '.$e->getCode()."\n";
	$msg .= 'file: '.$e->getFile()."\n";
	$msg .= 'line: '.$e->getLine()."\n";
	$msg .= 'trace string: '.$e->getTraceAsString()."\n";
	$msg .= 'trace array: '.var_export($e->getTrace(), true);
	import_log($msg, true);
}
?>