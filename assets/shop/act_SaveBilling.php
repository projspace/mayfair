<?
	$db->Execute(
		sprintf("
			INSERT INTO
				shop_user_addresses
			SET
				account_id=%u
				,type = 'billing'
				,name=%s
				,email=%s
				,phone=%s
				,line1=%s
				,line2=%s
				,line3=%s
				,line4=%s
				,postcode=%s
				,country_id=%u
		"
			,$user_session->account_id
			,$db->Quote(safe($_REQUEST['name']))
			,$db->Quote(safe($_REQUEST['email']))
			,$db->Quote(safe($_REQUEST['phone']))
			,$db->Quote(safe($_REQUEST['line1']))
			,$db->Quote(safe($_REQUEST['line2']))
			,$db->Quote(safe($_REQUEST['line3']))
			,$db->Quote(safe($_REQUEST['line4']))
			,$db->Quote(safe($_REQUEST['postcode']))
			,safe($_REQUEST['country_id'])
		)
	);
	$address_id=$db->Insert_ID();
	header("location: ".$config['dir']."billing?act=chooseAddress&address_id=".$address_id);exit;
?>