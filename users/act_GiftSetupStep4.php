<?
	do
	{
		$code = strtoupper(substr(trim($_SESSION['gift_setup']['surname']), 0, 3)).mt_rand(10000, 99999);
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
			,$db->Quote(safe($_SESSION['gift_setup']['email']))
			,$db->Quote(safe($_POST['password']))
			,$db->Quote(safe($_SESSION['gift_setup']['title']))
			,$db->Quote(safe($_SESSION['gift_setup']['first_name']))
			,$db->Quote(safe($_SESSION['gift_setup']['middle_name']))
			,$db->Quote(safe($_SESSION['gift_setup']['surname']))
			,$db->Quote(safe($_SESSION['gift_setup']['primary_phone']))
			,$db->Quote(safe($_SESSION['gift_setup']['secondary_phone']))
			,$db->Quote(safe($_SESSION['gift_setup']['contact_method']))
			,$_SESSION['gift_setup']['newsletter']
		)
	);
	$err_no = $db->ErrorNo();
	$err_msg = $db->ErrorMsg();
	$user_id=$db->Insert_ID();
	if($user_id && !$err_no)
	{
		$name = array();
		if($value = trim($_SESSION['gift_setup']['first_name']))
			$name[]  = $value;
		if($value = trim($_SESSION['gift_setup']['middle_name']))
			$name[]  = $value;
		if($value = trim($_SESSION['gift_setup']['surname']))
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
					,postcode=%s
					,country_id=%u
					,delivery=%u
					,type = 'delivery'
			"
				,$user_id
				,$db->Quote(safe(implode(' ', $name)))
				,$db->Quote(safe($_SESSION['gift_setup']['email']))
				,$db->Quote(safe($_SESSION['gift_setup']['primary_phone']))
				,$db->Quote(safe($_SESSION['gift_setup']['address1']))
				,$db->Quote(safe($_SESSION['gift_setup']['address2']))
				,$db->Quote(safe($_SESSION['gift_setup']['address3']))
				,$db->Quote(safe($_SESSION['gift_setup']['postcode']))
				,$_SESSION['gift_setup']['country_id']
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
						,delivery_address_id=%u
						,date=%s
						,code=%s
						,name=%s
						,public=%u
						,delivery_after=%s
						,created=NOW()
				"
					,$user_id
					,$_SESSION['gift_setup']['type_id']
					,$address_id
					,$db->Quote(safe(implode('-', array_reverse(explode('/', $_SESSION['gift_setup']['date'])))))
					,$db->Quote(safe($code))
					,$db->Quote(safe($_SESSION['gift_setup']['name']))
					,$_POST['public']
					,$db->Quote(safe(implode('-', array_reverse(explode('/', $_SESSION['gift_setup']['delivery_after'])))))
				)
			);
			$list_id=$db->Insert_ID();
		}
	}
	
	$ok=$db->CompleteTrans();
	if($ok)
	{
		$redirect_url = $config["dir"].'gift-registry/setup?step=2&code='.$code;
		$user_session->start($user_id);
	}
	else
		$validator->invalidate('', '', 'Unable to create your list. Please check your details and try again.');
?>