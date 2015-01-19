<?
	include("../lib/cfg_Config.php");
	include("../lib/adodb/adodb.inc.php");
	include("../lib/act_OpenDB.php");
	include("../lib/lib_Session.php");
	include("../lib/lib_Common.php");
	include("../lib/lib_UserSession.php");
	
	try
	{
		if(!$user_session->check())
			throw new Exception('You are no longer logged in. Please login again.', 10000);
		
		$db->SetTransactionMode("SERIALIZABLE");
		$db->StartTrans();
		
		$db->Execute(
			$sql = sprintf("
				INSERT INTO
					shop_user_addresses
				SET
					account_id = %u
			"
				,$user_session->account_id
			)
		);
		$address_id=$db->Insert_ID();
		
		$ok=$db->CompleteTrans();
		if(!$ok)
			throw new Exception("There was a problem whilst adding the address, please try again.", 10001);
			
		die(json_encode(array('status'=>true, 'message'=>$address_id)));
	}
	catch(Exception $e)
	{
		if($e->getCode() >= 10000)
			$msg = $e->getMessage();
		else
			$msg = 'There was a problem whilst processing your request, please try again.';
			
		die(json_encode(array('status'=>false, 'message'=>$msg)));
	}
?>