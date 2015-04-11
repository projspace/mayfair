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
	$cfg_import['filename_import'] = $config['path'].'script/ean_upc.csv';
	
	$cfg_import['archive_import_file'] = true;
	$cfg_import['filename_archive'] = $config['path'].'script/archive/ean_upc_'.date('d.m.Y-H.i.s', $cfg_import['start_time']).'-'.$cfg_import['import_id'].'.csv';
	
	$cfg_import['log_file'] = true;
	$cfg_import['filename_log'] = $config['path'].'script/logs/ean_upc_'.date('d.m.Y-H.i.s', $cfg_import['start_time']).'-'.$cfg_import['import_id'].'.log';
	
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
	
	$log_url = str_replace($config['path'], $config['protocol'].$config['url'].$config['dir'], $cfg_import['filename_log']);
	if(!$display)
		echo "<br />\nLog file: <a href='".$log_url."'>".$log_url."</a><br />\n";
	
	import_log('Start import', !$display);
	
	if(!file_exists($cfg_import['filename_import']))
		throw new Exception('Import file does not exist.');
		echo '';
	if(!is_readable($cfg_import['filename_import']))
		throw new Exception('Import file does not have read permission.');
		
	$archived = true;
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
	
	$affected = 0;
	$line = 0;
	$prices = array();
	while(($data = fgetcsv($fp, 0, "\t")) !== false)
	{
		$line++;
		if($line == 1)
			continue;
			
		$data = array_map('trim', $data);
		
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
				,$db->Quote($data[6])
				,$db->Quote($data[0])
		));
		$product = $product->FetchRow();
		if(!$product)
		{
			import_log('Product '.$data[0].' not found', !$display);
			continue;
		}

		if($product['option_id']+0)
		{
			$db->Execute(
				$sql = sprintf("
					UPDATE
						shop_product_options
					SET
						ean_code = %s
					WHERE
						id = %u
				"
					,$db->Quote(trim($data[5]))
					,$product['option_id']
			));
		}
			
		$prices[$product['id']] = $data[7];
	}
	
	foreach($prices as $product_id=>$price)
		$db->Execute(
			$sql = sprintf("
				UPDATE
					shop_products
				SET
					price = %f
				WHERE
					id = %u
			"
				,$price
				,$product_id
		));
	
	
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