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
	$cfg_import['filename_import'] = $config['path'].'script/product_update.csv';
	
	$cfg_import['archive_import_file'] = false;
	$cfg_import['filename_archive'] = $config['path'].'script/archive/product_update_'.date('d.m.Y-H.i.s', $cfg_import['start_time']).'-'.$cfg_import['import_id'].'.csv';
	
	$cfg_import['log_file'] = true;
	$cfg_import['filename_log'] = $config['path'].'script/logs/product_update_'.date('d.m.Y-H.i.s', $cfg_import['start_time']).'-'.$cfg_import['import_id'].'.log';
	
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
	
	$search=new Search($config);
	
	$line = 0;
	while(($data = fgetcsv($fp, 0)) !== false)
	{
		$line++;
		if($line == 1)
			continue;
			
		$data = array_map('trim', $data);
		
		$product=$db->Execute(
			sprintf("
				SELECT
					*
				FROM
					shop_products
				WHERE
					id=%u
			"
				,$data[0]
			)
		);
		$product = $product->FetchRow();
		if(!$product)
		{
			import_log("Product not found: id: ".$data[0], true);
			continue;
		}
		
		$db->Execute(
			sprintf("
				UPDATE
					shop_products
				SET
					name = %s
					,description = %s
					,meta_title = %s
					,meta_description = %s
					,meta_keywords = %s
				WHERE
					id=%u
				LIMIT 1
			"
				,$db->Quote($data[1])
				,$db->Quote($data[2])
				,$db->Quote($data[3])
				,$db->Quote($data[4])
				,$db->Quote($data[5])
				,$product['id']
			)
		);

		$search->update("product",$product['id'],$data[1],strip_tags($data[2]),array('code'=>$product['code'], 'description'=>strip_tags($product['short_description'])));
		
		import_log("Updating product: \nid: ".$data[0]."\nname: ".$data[1], true);
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