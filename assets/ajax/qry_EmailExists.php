<?
	include("../lib/cfg_Config.php");
	include("../lib/adodb/adodb.inc.php");
	include("../lib/act_OpenDB.php");
	
	
	try {
		$exists = $db->Execute($q = sprintf("SELECT COUNT(*) as `total` FROM `shop_user_accounts` WHERE `email` = %s;"
										,$db->Quote($_POST['email'])));
		$exists = $exists->FetchRow();

		die(json_encode(array('status'=>true, 'message'=>$exists)));
	} catch (Exception $e) {
		die(json_encode(array('status'=>false, 'message'=>$msg)));
	}
	
	include("../lib/act_CloseDB.php");
	die(json_encode($vars));