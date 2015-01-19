<?
error_reporting(E_ALL);
ini_set('display_errors','1');
set_time_limit(0);
try 
{
	if(!isset($config))
	{
		include("../lib/cfg_Config.php");
		include("../lib/adodb/adodb.inc.php");
		include("../lib/act_OpenDB.php");
		include("../lib/lib_CommonAdmin.php");
		
		$display = false;
	}
	else
		$display = true;

	$cfg_import['start_time'] = time();
	$cfg_import['import_id'] = uuid();
	$cfg_import['filename_import'] = $config['path'].'script/stock.csv';
	
	$cfg_import['archive_import_file'] = true;
	$cfg_import['filename_archive'] = $config['path'].'script/archive/stock_'.date('d.m.Y-H.i.s', $cfg_import['start_time']).'-'.$cfg_import['import_id'].'.csv';
	
	$cfg_import['log_file'] = true;
	$cfg_import['filename_log'] = $config['path'].'script/logs/stock_'.date('d.m.Y-H.i.s', $cfg_import['start_time']).'-'.$cfg_import['import_id'].'.log';
	
	$cfg_import['header_columns'] = array(
		0 => array(
			'header' => strtolower('Warehouse')
			,'required' => false
			,'column' => ''
		)
		,array(
			'header' => strtolower('Style')
			,'required' => true
			,'column' => ''
		)
		,array(
			'header' => strtolower('Style Description')
			,'required' => false
			,'column' => ''
		)
		,array(
			'header' => strtolower('Color')
			,'required' => true
			,'column' => ''
		)
		,array(
			'header' => strtolower('SizeType')
			,'required' => true
			,'column' => ''
		)
		,array(
			'header' => strtolower('EAN Code')
			,'required' => true
			,'column' => ''
		)
		,array(
			'header' => strtolower('Units on Hand')
			,'required' => true
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
		$upc_codes = array();
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
				else
				{
					if(isset($upc_codes[$data[5]]))
						$upc_codes[$data[5]]++;
					else
						$upc_codes[$data[5]] = 1;
				}
			}
		}
		
		if(fseek($handle, $pointer) == -1)
			throw new Exception('Cannot rewind import file');
			
		$duplicates = array();
		foreach($upc_codes as $code=>$count)
			if($count > 1)
				$duplicates[] = $code;
		
		if(count($duplicates))
		{
			import_log('Duplicate EAN codes found: '.implode(',', $duplicates));
			$error_count++;
		}
		
		return ($error_count)?false:true;
	}
	
	function true_price($price)
	{
		return str_replace(',','.',$price);
	}
	
	$log_url = str_replace($config['path'], $config['protocol'].$config['url'].$config['dir'], $cfg_import['filename_log']);
	if(!$display)
		echo "<br />\nLog file: <a href='".$log_url."'>".$log_url."</a><br />\n";
	
	import_log('Start import', !$display);
	
	if(!file_exists($cfg_import['filename_import']))
		throw new Exception('Import file does not exist.');
		echo '';
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
	
	$affected = 0;
	$line = 0;
	while(($data = fgetcsv($fp, 0)) !== false)
	{
		$line++;
		if($line == 1)
			continue;
			
		$data = array_map('trim', $data);
		
		$quantity = ($data[6]+0 > 0)?$data[6]:0;
		
		$product = $db->Execute(
			$sql = sprintf("
				SELECT
					shop_products.id
					,shop_product_options.id option_id
				FROM
					shop_products
				LEFT JOIN
					shop_product_options
				ON
					shop_product_options.product_id = shop_products.id
				AND
					shop_product_options.upc_code = %s
				WHERE
					shop_products.code = %s
			"
				,$db->Quote($data[5])
				,$db->Quote($data[1])
		));
		$product = $product->FetchRow();
		if(!$product)
		{
			import_log('Product '.$data[1].' not found', !$display);
			continue;
		}
		
		if(!($product['option_id']+0))
		{
			import_log('Option '.$data[5].' not found', !$display);
			// color
			$color = $db->Execute(
				$sql = sprintf("
					SELECT
						id
					FROM
						shop_colors
					WHERE
						code = %s
				"
					,$db->Quote($data[3])
			));
			$color = $color->FetchRow();
			if(!$color)
			{
				$db->Execute(
					$sql = sprintf("
						INSERT INTO
							shop_colors
						SET
							name = %s
							,code = %s
							,ord = IFNULL((SELECT MAX(sc.ord)+1 FROM shop_colors sc), 1)
					"
						,$db->Quote($data[3])
						,$db->Quote($data[3])
				));
				$color_id=$db->Insert_ID();
				if(!$color_id)
					throw new Exception("Unable to create color");
				import_log('Color '.$data[3].' created', !$display);
			}
			else
				$color_id = $color['id']+0;
			
			$size_code = explode('-', $data[4]);
			if(count($size_code) <= 1)
			{
				$size_code = trim(array_shift($size_code));
				$width_code = '';
			}
			else
			{
				$width_code = trim(array_shift($size_code));
				$size_code = trim(implode('-', $size_code));
			}
			
			// width
			if($width_code != '' && $width_code != '_')
			{
				$width = $db->Execute(
					$sql = sprintf("
						SELECT
							id
						FROM
							shop_widths
						WHERE
							code = %s
					"
						,$db->Quote($width_code)
				));
				$width = $width->FetchRow();
				if(!$width)
				{
					$db->Execute(
						$sql = sprintf("
							INSERT INTO
								shop_widths
							SET
								name = %s
								,code = %s
								,ord = IFNULL((SELECT MAX(sw.ord)+1 FROM shop_widths sw), 1)
						"
							,$db->Quote($width_code)
							,$db->Quote($width_code)
					));
					$width_id=$db->Insert_ID();
					if(!$width_id)
						throw new Exception("Unable to create width");
					import_log('Width '.$width_code.' created', !$display);
				}
				else
					$width_id = $width['id']+0;
			}
			else
				$width_id = 'NULL';
				
			// size
			$size = $db->Execute(
				$sql = sprintf("
					SELECT
						id
					FROM
						shop_sizes
					WHERE
						name = %s
				"
					,$db->Quote($size_code)
			));
			$size = $size->FetchRow();
			if(!$size)
			{
				$db->Execute(
					$sql = sprintf("
						INSERT INTO
							shop_sizes
						SET
							name = %s
							,ord = IFNULL((SELECT MAX(ss.ord)+1 FROM shop_sizes ss), 1)
					"
						,$db->Quote($size_code)
				));
				$size_id=$db->Insert_ID();
				if(!$size_id)
					throw new Exception("Unable to create size");
				import_log('Size '.$size_code.' created', !$display);
			}
			else
				$size_id = $size['id']+0;
			
			$db->Execute(
				$sql = sprintf("
					INSERT INTO
						shop_product_options
					SET
						product_id = %u
						,upc_code = %s
						,size_id = %u
						,width_id = %s
						,color_id = %u
						,quantity = %u
				"
					,$product['id']
					,$db->Quote($data[5])
					,$size_id
					,$width_id
					,$color_id
					,$quantity
			));
			$option_id=$db->Insert_ID();
			if(!$option_id)
				throw new Exception("Unable to create option");
			import_log('Option '.$data[5].' created', !$display);
		}
		else
		{
			$option_id = $product['option_id']+0;
			
			$db->Execute(
				$sql = sprintf("
					UPDATE
						shop_product_options
					SET
						quantity = %u
					WHERE
						id = %u
				"
					,$quantity
					,$option_id
			));
		}
	}
	
	$ok=$db->CompleteTrans();
	if(!$ok)
		throw new Exception("Database error:\nerror no: ".$db->ErrorNo()."\nerror msg: ".$db->ErrorMsg());
	
	import_log('Import ended succesfully.', !$display);
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
	import_log($msg, !$display);
	
	if($display)
		error($e->getMessage().' <a href="'.str_replace($config['path'], $config['dir'], $cfg_import['filename_log']).'" target="_blank">Click here for more details</a>');
}
@unlink($cfg_import['filename_import']);
?>