<?
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();
	
	$db->Execute(
		sprintf("
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
				,billing=%u
				,delivery=%u
		"
			,$user_session->account_id
			,$db->Quote(safe($_POST['name']))
			,$db->Quote(safe($_POST['email']))
			,$db->Quote(safe($_POST['phone']))
			,$db->Quote(safe($_POST['line1']))
			,$db->Quote(safe($_POST['line2']))
			,$db->Quote(safe($_POST['line3']))
			,$db->Quote(safe($_POST['line4']))
			,$db->Quote(safe($_POST['postcode']))
			,safe($_POST['country_id'])
			,safe($_POST['billing'])
			,safe($_POST['delivery'])
		)
	);
	$address_id=$db->Insert_ID();
	if($address_id)
	{
		if($_POST['billing']+0)
			$db->Execute(
				sprintf("
					UPDATE
						shop_user_addresses
					SET
						billing = 0
					WHERE
						account_id = %u
					AND
						id != %u
				"
					,$user_session->account_id
					,$address_id
				)
			);
		if($_POST['delivery']+0)
			$db->Execute(
				sprintf("
					UPDATE
						shop_user_addresses
					SET
						delivery = 0
					WHERE
						account_id = %u
					AND
						id != %u
				"
					,$user_session->account_id
					,$address_id
				)
			);
	}
	
	$ok=$db->CompleteTrans();
?>