<?
	do
	{
		$code = strtoupper(substr(trim($_POST['surname']), 0, 3)).mt_rand(10000, 99999);
		$result = $db->Execute(sprintf("SELECT id FROM gift_lists WHERE code=%s", $db->Quote($code)));
	}
	while($row = $result->FetchRow());
	
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();
	
	$db->Execute(
		$sql = sprintf("
			INSERT INTO
				shop_user_accounts
			SET
				email=%s
				,password=%s
				,title=%s
				,firstname=%s
				,middlename=%s
				,lastname=%s
				,primary_phone=%s
				,secondary_phone=%s
				,contact_method=%s
				,newsletter=%u
				,created = NOW()
		"
			,$db->Quote(safe($_POST['email']))
			,$db->Quote(safe($_POST['password']))
			,$db->Quote(safe($_POST['title']))
			,$db->Quote(safe($_POST['first_name']))
			,$db->Quote(safe($_POST['middle_name']))
			,$db->Quote(safe($_POST['surname']))
			,$db->Quote(safe($_POST['primary_phone']))
			,$db->Quote(safe($_POST['secondary_phone']))
			,$db->Quote(safe($_POST['contact_method']))
			,$_POST['newsletter']
		)
	);
	$err_no = $db->ErrorNo();
	$err_msg = $db->ErrorMsg();
	$user_id=$db->Insert_ID();
	if($user_id && !$err_no)
	{
		$name = array();
		if($value = trim($_POST['first_name']))
			$name[]  = $value;
		if($value = trim($_POST['middle_name']))
			$name[]  = $value;
		if($value = trim($_POST['surname']))
			$name[]  = $value;
		
		$db->Execute(
			$sql = sprintf("
				INSERT INTO
					shop_user_addresses
				SET
					account_id=%u
					,name=%s
					,email=%s
					,phone=%s
					,line1=%s
					,line2=%s
					,line3=%s
					,line4=%s
					,postcode=%s
					,country_id=%u
					,delivery=%u
					,type = 'delivery'
			"
				,$user_id
				,$db->Quote(safe(implode(' ', $name)))
				,$db->Quote(safe($_POST['email']))
				,$db->Quote(safe($_POST['primary_phone']))
				,$db->Quote(safe($_POST['address1']))
				,$db->Quote(safe($_POST['address2']))
				,$db->Quote(safe($_POST['address3']))
				,$db->Quote(safe($_POST['address4']))
				,$db->Quote(safe($_POST['postcode']))
				,$_POST['country_id']
				,1
			)
		);
		$err_no = $db->ErrorNo();
		$err_msg = $db->ErrorMsg();
		$address_id=$db->Insert_ID();
		if($address_id && !$err_no)
		{
			$db->Execute(
				$sql = sprintf("
					INSERT INTO
						gift_lists
					SET
						account_id=%u
						,type_id=%u
						,other_type=%s
						,delivery_address_id=%u
						,date=%s
						,code=%s
						,name=%s
						,public=%u
						,delivery_after=%s
						,created=NOW()
				"
					,$user_id
					,$_POST['type_id']
                    ,$db->Quote(safe($_POST['other_type']))
					,$address_id
					,$db->Quote(safe(get_date($_POST['date'])))
					,$db->Quote(safe($code))
					,$db->Quote(safe($_POST['name']))
					,$_POST['public']
                    ,$db->Quote(safe(get_date($_POST['delivery_after'])))
				)
			);
			$list_id=$db->Insert_ID();
		}

        // Create Authorize Profile Id
        if ($config['psp']['driver'] == 'Authorize' ) {
            include ("../lib/lib_Payment.php");
            include ("../lib/payment/cfg_Authorize.php");
            include ("../lib/payment/lib_Authorize.php");

            $psp = new Authorize($config,$smarty,$db);

            if ( $authorize_profile_id = $psp->CreateCustomerProfile($user_id,safe($_POST['email'])))
                include ("act_UpdateAuthorizeProfileId.php");
        }
	}
	
	$ok=$db->CompleteTrans();
	if($ok)
	{
		$redirect_url = $config["dir"].'gift-registry/setup?step=2&code='.$code;

        $mail->sendMessage(array('link'=>$config["dir"].'index.php?fuseaction=admin.viewGiftRegistry&list_id='.$list_id),"NewGiftRegistry",$config['mail']['notify'],"");

		$user_session->start($user_id);
	}
	else
		$validator->invalidate('', '', 'Unable to create your list. Please check your details and try again.');
?>