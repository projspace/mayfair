<?
	error_reporting(E_ALL);
	ini_set('display_errors','1');
	set_time_limit(0);
	try 
	{
		include("../lib/cfg_Config.php");
		include("../lib/adodb/adodb.inc.php");
		include("../lib/act_OpenDB.php");
		include("../lib/lib_Common.php");
		include("../lib/lib_Email.php");
		
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
		
		$cfg_import['start_time'] = time();
		$cfg_import['import_id'] = uuid();
		$cfg_import['log_file'] = true;
		$cfg_import['filename_log'] = $config['path'].'script/logs/order_report/order_report_'.date('Y-m-d-H.i.s', $cfg_import['start_time']).'-'.$cfg_import['import_id'].'.log';
		
		$orders = $db->Execute(
			sprintf("
				SELECT
					*
				FROM
					shop_orders
				WHERE
					DATE(FROM_UNIXTIME(`captured`)) = CURDATE()
			"
			)
		);
		if(!$orders->RecordCount())
		{
			import_log("No orders for today", true);
			return;
		}
			
		$file = 'order_report_'.time().md5(uniqid(rand(), true)).'.csv';
		if(!$handle = fopen($filename = $config['path'].'downloads/temp/'.$file, 'w'))
			throw new Exception('Cannot open csv file');
		
		$csv = array();
		$csv[] = "Order ID";
		$csv[] = "Order Date";
		$csv[] = "Subotal";
		$csv[] = "Shipping";
		$csv[] = "Packing";
		$csv[] = "Tax";
		$csv[] = "Total";
		fwrite($handle, '"'.implode('","', $csv).'"'."\n");
		
		while($row=$orders->FetchRow())
		{
			$csv = array();
			$csv[] = $row['id'];
			$csv[] = date('d/m/Y H:i', $row['time']);
			$csv[] = $row['total'];
			$csv[] = $row['shipping'];
			$csv[] = $row['packing'];
			$csv[] = $row['tax'];
			$csv[] = $row['total']+$row['shipping']+$row['packing']+$row['tax'];

			fwrite($handle, '"'.implode('","', $csv).'"'."\n");
		}
		fclose($handle);
		
		if(!$mail->sendMessage(array(),"OrderReport",'','', array($filename)))
		{
			@copy($filename, $config['path'].'script/archive/order_report/'.$file);
			throw new Exception('Cannot send email');
		}	
		@unlink($filename);
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