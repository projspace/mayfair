<?
	$exists=$db->Execute(
		sprintf("
			SELECT
				id
			FROM
				shop_user_accounts
			WHERE
				email = %s
		"
			,$db->Quote($_POST['email'])
		)
	);
	if($exists->FetchRow())
	{
		error("This customer already exists. Please choose another email address.","Stop");
		$ok = false;
		return;
	}
	
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();

	$db->Execute(
		sprintf("
			INSERT
				shop_user_accounts
			SET
				email = %s
				,firstname = %s
				,lastname = %s
				,primary_phone = %s
				,password = %s
				,info = %s
				,dob = %s
				,student = %u
				,confirmed_student = %u
		"
			,$db->Quote($_POST['email'])
			,$db->Quote($_POST['firstname'])
			,$db->Quote($_POST['lastname'])
			,$db->Quote($_POST['phone'])
			,$db->Quote($_POST['password'])
			,$db->Quote($_POST['info'])
			,$db->Quote(implode('-', array_reverse(explode('/', $_POST['dob']))))
			,$_POST['student']
			,$_POST['confirmed_student']
		)
	);
	$user_id=$db->Insert_ID();

	if($user_id)
	{
		if(is_array(($_POST['shipping_name'])))
			foreach($_POST['shipping_name'] as $index=>$unused)
				$db->Execute(
					sprintf("
						INSERT INTO
							shop_user_addresses
						SET
							account_id = %u
							,name = %s
							,email = %s
							,phone = %s
							,line1 = %s
							,line2 = %s
							,postcode = %s
							,country_id = %u
							,type = 'delivery'
					"
						,$user_id
						,$db->Quote($_POST['shipping_name'][$index])
						,$db->Quote($_POST['shipping_email'][$index])
						,$db->Quote($_POST['shipping_phone'][$index])
						,$db->Quote($_POST['shipping_line1'][$index])
						,$db->Quote($_POST['shipping_line2'][$index])
						,$db->Quote($_POST['shipping_postcode'][$index])
						,$_POST['shipping_country_id'][$index]
					)
				);
				
		//billing addresses
		if(is_array(($_POST['billing_name'])))
			foreach($_POST['billing_name'] as $index=>$unused)
				$db->Execute(
					sprintf("
						INSERT INTO
							shop_user_addresses
						SET
							account_id = %u
							,name = %s
							,email = %s
							,phone = %s
							,line1 = %s
							,line2 = %s
							,postcode = %s
							,country_id = %u
							,type = 'billing'
					"
						,$user_id
						,$db->Quote($_POST['billing_name'][$index])
						,$db->Quote($_POST['billing_email'][$index])
						,$db->Quote($_POST['billing_phone'][$index])
						,$db->Quote($_POST['billing_line1'][$index])
						,$db->Quote($_POST['billing_line2'][$index])
						,$db->Quote($_POST['billing_postcode'][$index])
						,$_POST['billing_country_id'][$index]
					)
				);
	}
	
	$ok=$db->CompleteTrans();
	if(!$ok)
		error("There was a problem whilst insert the user, please try again.  If this problem persists please contact your designated support contact","Database Error");
?>