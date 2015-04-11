<?
	try 
	{
		$cfg_import['start_time'] = time();
		$cfg_import['import_id'] = uuid();
		$cfg_import['filename_import'] = $config['path'].'confirmation/'.$filename;
		
		$cfg_import['archive_import_file'] = true;
		$cfg_import['filename_archive'] = $config['path'].'script/archive/fc/'.date('Y-m-d', $cfg_import['start_time']).'/';
		if(!is_dir($cfg_import['filename_archive']))
		{
			@mkdir($cfg_import['filename_archive'], 0777);
			@chmod($cfg_import['filename_archive'], 0777);
		}
		$cfg_import['filename_archive'] .= $filename.'_'.date('Y-m-d-H.i.s', $cfg_import['start_time']).'-'.$cfg_import['import_id'].'.txt';
		
		$cfg_import['log_file'] = true;
		$cfg_import['filename_log'] = $config['path'].'script/logs/fc/'.date('Y-m-d', $cfg_import['start_time']).'/';
		if(!is_dir($cfg_import['filename_log']))
		{
			@mkdir($cfg_import['filename_log'], 0777);
			@chmod($cfg_import['filename_log'], 0777);
		}
		$cfg_import['filename_log'] .= $filename.'_'.date('Y-m-d-H.i.s', $cfg_import['start_time']).'-'.$cfg_import['import_id'].'.log';
		
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
		
		$products = array();
		$line = 0;
		while(($data = fgetcsv($fp, 0, "\t")) !== false)
		{
			$line++;
			/*if($line == 1)
				continue;*/
				
			$data = array_map('trim', $data);
			switch(count($data))
			{
				case 16:
					$product_key = 3;
					$price_key = 11;
					$weight_key = 12;
					break;
				case 14:
					$product_key = 2;
					$price_key = 10;
					$weight_key = 11;
					break;
				default:
					if(!$archived)
						@copy($cfg_import['filename_import'], $cfg_import['filename_archive']);
					throw new Exception('Invalid structure: '.count($data).' columns');
					break;
			}
			
			/* 
				all weight is in pounds / lbs and ounces BUT in percentages - so 1% = 1 pound and 0.5% = 1/2 pound or 8 ounces. (1lb = 16 ounces)  
				1 pound = 0.45359237 kilograms = 453.59237 grams
			*/
			if($data[$product_key] != '' && !isset($products[$data[$product_key]]))
				$products[$data[$product_key]] = array('price' => $data[$price_key], 'weight' => round($data[$weight_key] * 453.59237));
		}
		
		fclose($fp);
		
		foreach($products as $code=>$row)
		{
			$db->Execute(
				$sql = sprintf("
					UPDATE
						shop_products
					SET
						price = %f
						,weight = %f
					WHERE
						code = %s
					LIMIT 1
				"
					,$row['price']
					,$row['weight']
					,$db->Quote($code)
				)
			);
			if($db->Affected_Rows())
				import_log('Product '.$code.' modified; price: '.$row['price'].' weight: '.$row['weight']);
		}
			
		$ok=$db->CompleteTrans();
		if(!$ok)
			throw new Exception("Database error:\nerror no: ".$db->ErrorNo()."\nerror msg: ".$db->ErrorMsg());
		
		@unlink($cfg_import['filename_import']);
		
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