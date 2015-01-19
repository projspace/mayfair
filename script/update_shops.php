<?
error_reporting(E_ALL);
ini_set('display_errors','1');
set_time_limit(0);
try 
{
	define('USECOOKIE',true);
	define('SEARCHENGINE',false);
	
	include("../lib/cfg_Config.php");
	include("../lib/adodb/adodb.inc.php");
	include("../lib/act_OpenDB.php");
	include("../lib/lib_Smarty.php");
	include("../lib/lib_CommonAdmin.php");
	
	include ("../lib/lib_Payment.php");
	include ("../lib/payment/cfg_Authorize.php");
	include ("../lib/payment/lib_Authorize.php");
	
	$cfg_import['start_time'] = time();
	$cfg_import['import_id'] = uuid();
	$cfg_import['filename_import'] = $config['path'].'script/update_shops.csv';
	
	$cfg_import['archive_import_file'] = false;
	$cfg_import['filename_archive'] = $config['path'].'script/archive/update_shops_'.date('d.m.Y-H.i.s', $cfg_import['start_time']).'-'.$cfg_import['import_id'].'.csv';
	
	$cfg_import['log_file'] = true;
	$cfg_import['filename_log'] = $config['path'].'script/logs/update_shops_'.date('d.m.Y-H.i.s', $cfg_import['start_time']).'-'.$cfg_import['import_id'].'.log';
	
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
	
	$postcodes = array();
	$line = 0;
	while(($data = fgetcsv($fp, 0)) !== false)
	{
		$line++;
		if($line == 1)
			continue;
			
		$data = array_map('trim', $data);
		
		$lat = 0;
		$long = 0;
		if($response = get_google_coords($data[1]." ".$data[2]." ".$data[3]." ".$data[4]))
		{
			$lat = $response['lat']+0;
			$long = $response['long']+0;
		}
		else
			import_log('Cannot get lat&lng for row '.$line.': '.var_export($data, true), true);
		
		$postcodes[] = $data[4];
		
		$shop = $db->Execute(
			$sql = sprintf("
				SELECT
					*
				FROM
					shop_user_shops
				WHERE
					zip = %s
			"
				,$db->Quote($data[4])
			)
		);
		$shop = $shop->FetchRow();
		if($shop) //update shop
		{
			$db->Execute(
				sprintf("
					UPDATE
						shop_user_shops
					SET
						name = %s
						,address1 = %s
						,address2 = ''
						,city = %s
						,state = %s
						,phone = %s
						,lat = %f
						,`long` = %f
						,hidden = 0
					WHERE
						id=%u
				"
					,$db->Quote($data[0])
					,$db->Quote($data[1])
					,$db->Quote($data[2])
					,$db->Quote($data[3])
					,$db->Quote($data[5])
					,$lat
					,$long
					,$shop['id']
				)
			);
			if($db->ErrorNo())
				throw new Exception("DB error:\nerror no: ".$db->ErrorNo()."\nerror msg: ".$db->ErrorMsg());
		}
		elseif($data[0] != '') //insert shop
		{
			import_log('Inserting shop; line '.$line.': '.var_export($data, true), true);
			
			$email = str_replace("'","",$data[0]);
			$email = str_replace(",","",$email);
			$email = str_replace("-","",$email);
			$email = str_replace(" ","",$email);
			$email .= "@gmail.com";
			
			$account = $db->Execute(
				$sql = sprintf("
					SELECT
						*
					FROM
						shop_user_accounts
					WHERE
						email = %s
				"
					,$db->Quote($email)
				)
			);
			$account = $account->FetchRow();
			if(!$account)
			{
				import_log('Inserting account email: '.$email, true);
				
				$db->Execute(
					sprintf("
							INSERT
								shop_user_accounts
							SET
								email = %s
								,password = %s
						"
						,$db->Quote($email)
						,$db->Quote('password')
					)
				);
				if($db->ErrorNo())
					throw new Exception("DB error:\nerror no: ".$db->ErrorNo()."\nerror msg: ".$db->ErrorMsg());
				$user_id=$db->Insert_ID();
				if($user_id && $config['psp']['driver'] == 'Authorize') {
				
					$psp = new Authorize($config,$smarty,$db);
					if ( $authorize_profile_id = $psp->CreateCustomerProfile($user_id,$email))
						include ("../users/act_UpdateAuthorizeProfileId.php");
				}
			}
			else
				$user_id = $account['id'];
				
			if($user_id)
			{
				$db->Execute(
					$q=sprintf("
							INSERT
								shop_user_shops
							SET
								user_id = %u
								,name = %s
								,address1 = %s
								,city = %s
								,state = %s
								,zip = %s
								,phone = %s
								,rating = 1
								,lat = %f
								,`long` = %f
						"
						,$user_id
						,$db->Quote($data[0])
						,$db->Quote($data[1])
						,$db->Quote($data[2])
						,$db->Quote($data[3])
						,$db->Quote($data[4])
						,$db->Quote($data[5])
						,$lat
						,$long
					)
				);
				if($db->ErrorNo())
					throw new Exception("DB error:\nerror no: ".$db->ErrorNo()."\nerror msg: ".$db->ErrorMsg());
			}
			else
				import_log('Account id missing', true);
		}
		else
			import_log('Skipping row '.$line.' name empty: '.var_export($data, true), true);
	}
	
	if(count($postcodes))
	{
		$postcodes = array_map(array($db, 'Quote'), $postcodes);
		
		$db->Execute(
			$sql = sprintf("
				UPDATE
					shop_user_shops
				SET
					hidden = 1
				WHERE
					zip NOT IN (%s)
			"
				,implode(',', $postcodes)
			)
		);
		echo $sql;
		if($db->ErrorNo())
			throw new Exception("DB error:\nerror no: ".$db->ErrorNo()."\nerror msg: ".$db->ErrorMsg());
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