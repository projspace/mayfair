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
				case 9:
					$product_key = 3;
					$upc_key = 1;
					$quantity_key = 7;
					$availability_key = 8;
					break;
				case 8:
					$product_key = 2;
					$upc_key = 1;
					$quantity_key = 6;
					$availability_key = 7;
					break;
				default:
					if(!$archived)
						@copy($cfg_import['filename_import'], $cfg_import['filename_archive']);
					throw new Exception('Invalid structure: '.count($data).' columns');
					break;
			}
			
			$availability = strtoupper($data[$availability_key]);
			$code = $data[$product_key];
			$upc = $data[$upc_key];
			$quantity = $data[$quantity_key];
			
			if($availability = 'AT ONCE')
			{
				$db->Execute(
					$sql = sprintf("
						UPDATE
							shop_product_options
						JOIN
							shop_products
						ON
							shop_products.id = shop_product_options.product_id
						SET
							shop_product_options.quantity = %u
						WHERE
							shop_products.code = %s
						AND
							shop_product_options.upc_code = %s
					"
						,$quantity
						,$db->Quote($code)
						,$db->Quote($upc)
					)
				);
				if($db->Affected_Rows())
					import_log('Product '.$code.', upc '.$upc.' modified; quantity: '.$quantity);
			}
			else
				import_log('Ignoring product '.$code.', upc '.$upc.': not AT ONCE');
		}
		
		fclose($fp);
		
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