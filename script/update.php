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
	$cfg_import['filename_import'] = $config['path'].'script/update.csv';
	
	$cfg_import['archive_import_file'] = true;
	$cfg_import['filename_archive'] = $config['path'].'script/archive/update_'.date('d.m.Y-H.i.s', $cfg_import['start_time']).'-'.$cfg_import['import_id'].'.csv';
	
	$cfg_import['log_file'] = true;
	$cfg_import['filename_log'] = $config['path'].'script/logs/update_'.date('d.m.Y-H.i.s', $cfg_import['start_time']).'-'.$cfg_import['import_id'].'.log';
	
	$cfg_import['header_columns'] = array(
		0 => array(
			'header' => strtolower('plu')
			,'required' => true
			,'column' => ''
		)
		,array(
			'header' => strtolower('description')
			,'required' => false
			,'column' => ''
		)
		,array(
			'header' => strtolower('vatcode')
			,'required' => false
			,'column' => ''
		)
		,array(
			'header' => strtolower('price1')
			,'required' => true
			,'column' => ''
		)
		,array(
			'header' => strtolower('price2')
			,'required' => false
			,'column' => ''
		)
		,array(
			'header' => strtolower('price3')
			,'required' => false
			,'column' => ''
		)
		,array(
			'header' => strtolower('price4')
			,'required' => false
			,'column' => ''
		)
		,array(
			'header' => strtolower('price5')
			,'required' => false
			,'column' => ''
		)
		,array(
			'header' => strtolower('price6')
			,'required' => false
			,'column' => ''
		)
		,array(
			'header' => strtolower('held')
			,'required' => true
			,'column' => ''
		)
		,array(
			'header' => strtolower('exportdate')
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
		
		$db->Execute(
			$sql = sprintf("
				UPDATE
					shop_products
				SET
					price = %f
					,stock = %d
					,`updated` = NOW()
				WHERE
					code = %s
			"
				,true_price($data[3])
				,$data[9]
				,$db->Quote($data[0])
		));
		$affected += $db->Affected_Rows();
	}
	
	$ok=$db->CompleteTrans();
	if(!$ok)
		throw new Exception("Database error:\nerror no: ".$db->ErrorNo()."\nerror msg: ".$db->ErrorMsg());
	
	import_log('Import ended succesfully. Affected products: '.$affected, !$display);
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
		error($e->getMessage());
}
@unlink($cfg_import['filename_import']);
?>