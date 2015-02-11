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
			sprintf("
				UPDATE
					shop_user_accounts
				SET
					firstname = %s
					,lastname = %s
					,primary_phone = %s
					,info = %s
					,dob = %s
				WHERE
					id=%u
			"
				,$db->Quote(safe($_POST['firstname']))
				,$db->Quote(safe($_POST['lastname']))
				,$db->Quote(safe($_POST['primary_phone']))
				,$db->Quote(safe($_POST['info']))
				,$db->Quote(safe(implode('-', array_reverse(explode('/', $_POST['dob'])))))
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