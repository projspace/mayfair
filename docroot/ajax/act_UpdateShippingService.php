<?
	include("../lib/cfg_Config.php");
	include("../lib/adodb/adodb.inc.php");
	include("../lib/act_OpenDB.php");
	include("../lib/lib_Session.php");
	include("../lib/lib_Common.php");
	
	try
	{
		$db->SetTransactionMode("SERIALIZABLE");
		$db->StartTrans();
		
		$db->Execute(
			sprintf("
				UPDATE
					shop_sessions
				SET
					delivery_service_code = %s
				WHERE
					session_id = %s
			"
				,$db->Quote($_POST['delivery_service_code'])
				,$db->Quote($session->session_id)
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