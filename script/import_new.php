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
	
	include("../VLib/lib_VImage.php");
	include("../lib/lib_Search.php");

	$cfg_import['start_time'] = time();
	$cfg_import['import_id'] = uuid();
	$cfg_import['filename_import'] = $config['path'].'script/import_new.csv';
	
	$cfg_import['archive_import_file'] = false;
	$cfg_import['filename_archive'] = $config['path'].'script/archive/new_'.date('d.m.Y-H.i.s', $cfg_import['start_time']).'-'.$cfg_import['import_id'].'.csv';
	
	$cfg_import['log_file'] = true;
	$cfg_import['filename_log'] = $config['path'].'script/logs/new_'.date('d.m.Y-H.i.s', $cfg_import['start_time']).'-'.$cfg_import['import_id'].'.log';
	
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
	
	function get_category($column1, $column2)
	{
		global $db, $category_ord;
		
		$column2 = trim($column2);
		if(strtolower($column2) == 'deleted')
			return 1;
			
		$result = $db->Execute(
			$sql = sprintf("
				SELECT 
					shop_categories.id 
				FROM
					shop_categories
					,shop_categories sc
				WHERE
					shop_categories.parent_id = sc.id
				AND
					sc.name LIKE %s
				AND
					shop_categories.name LIKE %s
			"
				,$db->Quote('%'.trim($column1).'%')
				,$db->Quote('%'.trim($column2).'%')
		));
		$result = $result->FetchRow();
		return $result['id'];
	}
	
	function true_price($price)
	{
		return str_replace(array(',','£'),array('.',''),$price);
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
	
	$search=new Search($config);
	
	$line = 0;
	while(($data = fgetcsv($fp, 0)) !== false)
	{
		$line++;
		if($line == 1)
			continue;
			
		$data = array_map('trim', $data);
		
		$category_id = get_category($data[4],$data[5]);
		if(!$category_id+0)
			continue;
			
		if($data[0] == '')
		{
			$product = $db->Execute(
				$sql = sprintf("
					SELECT
						*
					FROM
						shop_products
					WHERE
						id = %u
				"
					,$product_id
				)
			);
			$product = $product->FetchRow();
			if(!$product)
				throw new Exception("Can't find product with id ".$product_id);
				
			if($data[3] != '')
			{
				$db->Execute(
					sprintf("
						UPDATE
							shop_products
						SET
							description=%s
						WHERE
							id=%u
					"
						,$db->Quote($product['description']."\n".'<p>'.$data[3].'</p>')
						,$product_id
					)
				);
			}
				
			$option = trim($data[9]);
			if($option != '')
			{
				$options=unserialize($product['options']);
				if(count($options))
				{
					$options[0]['value'][] = $option;
					$options[0]['price'][] = '';
					$options[0]['weight'][] = '';
					$options[0]['stock'][] = '';
				}
				else
					$options = array(array('name'=>'Sizes', 'value'=>array($option), 'price'=>array(''), 'weight'=>array(''), 'stock'=>array('')));
					
				$db->Execute(
					sprintf("
						UPDATE
							shop_products
						SET
							options=%s
						WHERE
							id=%u
					"
						,$db->Quote(serialize($options))
						,$product_id
					)
				);
			}
		}
		else
		{
			$option = trim($data[9]);
			if($option != '')
				$option = array(array('name'=>'Sizes', 'value'=>array($option), 'price'=>array(''), 'weight'=>array(''), 'stock'=>array('')));
			else
				$option = array();
			
			$db->Execute(
				$sql = sprintf("
					INSERT INTO
						shop_products
					SET
						category_id = %u
						,hidden = %u
						,code = %s
						,name = %s
						,vat = %u
						,price = %f
						,stock = %d
						,options = %s
						,specs = %s
						,description = %s
						,ord = %u
						,weight = 100
						,`inserted` = NOW()
						,`updated` = NOW()
				"
				,$category_id
				,0
				,$db->Quote($data[0])
				,$db->Quote($data[1])
				,($data[7]+0 > 0)?1:0
				,true_price($data[6])
				,1
				,$db->Quote(serialize($option))
				,$db->Quote(serialize(array(0=>array('name'=>'','value'=>''))))
				,$db->Quote('<p>'.$data[3].'</p>')
				,$product_ord[$category_id]+1
			));
			$product_id=$db->Insert_ID();
			if(!$product_id)
				throw new Exception("Can't create product:\nerror no: ".$db->ErrorNo()."\nerror msg: ".$db->ErrorMsg());
			
			if($data[2] != '')
			{
				if(is_file($filename = $config['path'].'script/tmp_images/'.$data[2].'.jpg'))
				{
					$type=multiple_resize($filename, $product_id, "product");
					if($type)
						$db->Execute(
							sprintf("
								UPDATE
									shop_products
								SET
									imagetype=%s
								WHERE
									id=%u
							"
								,$db->Quote($type)
								,$product_id
							)
						);
				}
				else
					import_log("File does not exist: ".$filename, true);
			}
			
			$search->add("product",$product_id,htmlentities($data[1], ENT_NOQUOTES, 'UTF-8'),htmlentities($data[3], ENT_NOQUOTES, 'UTF-8'));
			
			import_log("Creating product: \nname: ".$data[1]."\ncode: ".$data[0], true);
		}
		
		$tag = strtolower(trim($data[8]));
		if($tag != '')
		{	
			$article_tag = $db->Execute(
				sprintf("
					SELECT
						*
					FROM
						shop_meta_tags
					WHERE
						name LIKE %s
					LIMIT 1
				"
				,$db->Quote($tag)
				)
			);
			if(!$article_tag->RecordCount())
			{
				$db->Execute(
					sprintf("
						INSERT INTO
							shop_meta_tags
						SET
							name = %s
					"
					,$db->Quote($tag)
					)
				);
				$tag_id=$db->Insert_ID();
			}
			else
			{
				$article_tag = $article_tag->FetchRow();
				$tag_id = $article_tag['id'];
			}
				
			$db->Execute(
				$sql = sprintf("
					INSERT INTO
						shop_product_tags
					SET
						product_id = %u
						,tag_id = %u
				"
				,$product_id
				,$tag_id
			));
		}
	}
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