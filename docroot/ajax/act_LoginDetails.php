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
		
		include("../lib/lib_Validation.php");
		include("val_LoginDetails.php");
				
		if(!$validator->validate($_POST))
			throw new Exception('There seems to be some problems with your details: '."\n".implode("\n", $validator->_errorMsg), 10001);

		$db->SetTransactionMode("SERIALIZABLE");
		$db->StartTrans();
		
		$db->Execute(
			sprintf("
				UPDATE
					shop_user_accounts
				SET
					email = %s
					,password = %s
				WHERE
					id=%u
			"
				,$db->Quote(safe($_POST['email']))
				,$db->Quote(safe($_POST['password']))
				,$user_session->account_id
			)
		);
		
		$ok=$db->CompleteTrans();
		if(!$ok)
			throw new Exception("There was a problem whilst saving your details, please try again.", 10002);

        if ( !($user_session->session->fields['authorize_profile_id']+0) && $config['psp']['driver'] == 'Authorize' ) {
            include ("../lib/lib_Payment.php");
            include ("../lib/payment/cfg_Authorize.php");
            include ("../lib/payment/lib_Authorize.php");

            $psp = new Authorize($config,$smarty,$db);

            if ( $authorize_profile_id = $psp->CreateCustomerProfile($user_id = $user_session->account_id, safe($_POST['email'])))
                include ("../users/act_UpdateAuthorizeProfileId.php");
        }
			
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