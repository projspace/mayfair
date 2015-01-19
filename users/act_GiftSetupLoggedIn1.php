<?
	do
	{
		$code = strtoupper(substr(trim($user_session->session->fields['lastname']), 0, 3)).mt_rand(10000, 99999);
		$result = $db->Execute(sprintf("SELECT id FROM gift_lists WHERE code=%s", $db->Quote($code)));
	}
	while($row = $result->FetchRow());
	
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();
	
	$result = $db->Execute(sprintf("SELECT id FROM shop_user_addresses WHERE line1=%s AND line2=%s AND line3=%s AND postcode=%s AND country_id=%u AND account_id=%s"
		,$db->Quote(safe($_POST['address1']))
		,$db->Quote(safe($_POST['address2']))
		,$db->Quote(safe($_POST['address3']))
		,$db->Quote(safe($_POST['postcode']))
		,$_POST['country_id']
		,$user_session->account_id
	));
	$result = $result->FetchRow();
	if(!$result)
	{
		$name = array();
		if($value = trim($user_session->session->fields['firstname']))
			$name[]  = $value;
		if($value = trim($user_session->session->fields['middlename']))
			$name[]  = $value;
		if($value = trim($user_session->session->fields['lastname']))
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
					,type = 'delivery'
			"
				,$user_session->account_id
				,$db->Quote(safe(implode(' ', $name)))
				,$db->Quote(safe($user_session->session->fields['email']))
				,$db->Quote(safe($user_session->session->fields['primary_phone']))
				,$db->Quote(safe($_POST['address1']))
				,$db->Quote(safe($_POST['address2']))
				,$db->Quote(safe($_POST['address3']))
				,$db->Quote(safe($_POST['postcode']))
				,$_POST['country_id']
			)
		);
		$address_id = $db->Insert_ID();
	}
	else
		$address_id = $result['id'];
		
	if($address_id)
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
				,$user_session->account_id
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
	
	$ok=$db->CompleteTrans();
	if($ok)
	{
		$redirect_url = $config["dir"].'gift-registry/setup?step=2&code='.$code;

        $sent = $mail->sendMessage(array('link'=>$config["dir"].'index.php?fuseaction=admin.viewGiftRegistry&list_id='.$list_id),"NewGiftRegistry",$config['mail']['notify'],"");
	}
	else
		$validator->invalidate('', '', 'Unable to create your list. Please check your details and try again.');
?>