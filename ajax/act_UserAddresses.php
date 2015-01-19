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
		
		foreach($_POST['address'] as $address_id=>$address)
			$db->Execute(
				sprintf("
					UPDATE
						shop_user_addresses
					SET
						line1 = %s
						,billing = %u
						,delivery = %u
					WHERE
						id=%u
					AND
						account_id = %u
				"
					,$db->Quote(safe($address))
					,isset($_POST['billing'][$address_id])?1:0
					,isset($_POST['delivery'][$address_id])?1:0
					,$address_id
					,$user_session->account_id
				)
			);
		
		$ok=$db->CompleteTrans();
		if(!$ok)
			throw new Exception("There was a problem whilst saving your details, please try again.", 10001);
			
		die(json_encode(array('status'=>true, 'message'=>'')));
	}
	catch(Exception $e)
	{
		if($e->getCode() >= 10000)
			$msg = $e->getMessage();
		else
			$msg = 'There was a problem whilst processing your details, please try again.';
			
		die(json_encode(array('status'=>false, 'message'=>$msg)));
	}
?>