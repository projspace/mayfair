<?
	$exists=$db->Execute(
		sprintf("
			SELECT
				id
			FROM
				shop_user_accounts
			WHERE
				email = %s
			AND
				id != %u
		"
			,$db->Quote($_POST['email'])
			,$_POST['user_id']
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
			UPDATE
				shop_user_accounts
			SET
				email = %s
				,firstname = %s
				,lastname = %s
				,primary_phone = %s
				,info = %s
				,dob = %s
				,student = %u
				,confirmed_student = %u
				,teacher = %u
				,shop = %u
			WHERE
				id = %u
		"
			,$db->Quote($_POST['email'])
			,$db->Quote($_POST['firstname'])
			,$db->Quote($_POST['lastname'])
			,$db->Quote($_POST['phone'])
			,$db->Quote($_POST['info'])
			,$db->Quote(implode('-', array_reverse(explode('/', $_POST['dob']))))
			,$_POST['student']
			,$_POST['confirmed_student']
			,$_POST['teacher']
			,$_POST['shop']
			,$_POST['user_id']
		)
	);
    DBCheck(1);
	
	// save teacher info
	if($_POST['teacher']+0 && $_POST['teacherinfo'])
	{
		$db->Execute(
			sprintf('INSERT INTO
				`shop_user_teachers`
				(
				`user_id`,	`name`,		`zip`,		`phone`,	`website`,	`email`
				)
			VALUES
				(
				%1$u,		%2$s,		%3$s,		%4$s,		%5$s,		%6$s
				)
			ON DUPLICATE KEY UPDATE
				`name` = %2$s,
				`zip`  = %3$s,
				`phone` = %4$s,
				`website` = %5$s,
				`email` = %6$s
			'
			,$_POST['user_id']
			,$db->Quote($_POST['teacherinfo']['name'])
			,$db->Quote($_POST['teacherinfo']['zip'])
			,$db->Quote($_POST['teacherinfo']['phone'])
			,$db->Quote($_POST['teacherinfo']['website'])
			,$db->Quote($_POST['teacherinfo']['email'])
			)
		);
        DBCheck(2);
	}
	
	// save shop info
	if($_POST['shop']+0 && $_POST['shopinfo'])
	{
		
		// Get address location via google maps api
		if($data = get_google_coords($_POST['shopinfo']['address1']." ".$_POST['shopinfo']['address2']." ".$_POST['shopinfo']['city']." ".$_POST['shopinfo']['zip']))
		{
			$lat = $data['lat']+0;
			$long = $data['long']+0;
		}

		$shop=$db->Execute(
			sprintf("
				SELECT
					id
				FROM
					shop_user_shops
				WHERE
					user_id = %u
				AND
					hidden = 0
			"
				,$_POST['user_id']
			)
		);
		$shop = $shop->FetchRow();
		
		$sql = array();
		if(!$shop)
			$sql[] = sprintf("user_id=%u", $_POST['user_id']);
		$sql[] = sprintf("name=%s", $db->Quote($_POST['shopinfo']['name']));
		$sql[] = sprintf("address1=%s", $db->Quote($_POST['shopinfo']['address1']));
		$sql[] = sprintf("address2=%s", $db->Quote($_POST['shopinfo']['address2']));
		$sql[] = sprintf("city=%s", $db->Quote($_POST['shopinfo']['city']));
		$sql[] = sprintf("zip=%s", $db->Quote($_POST['shopinfo']['zip']));
		$sql[] = sprintf("phone=%s", $db->Quote($_POST['shopinfo']['phone']));
		$sql[] = sprintf("website=%s", $db->Quote($_POST['shopinfo']['website']));
		$sql[] = sprintf("email=%s", $db->Quote($_POST['shopinfo']['email']));
		$sql[] = sprintf("rating=%u", $_POST['shopinfo']['rating']);
		$sql[] = sprintf("`lat`=%f", $lat);
		$sql[] = sprintf("`long`=%f", $long);
				
		if($shop)
			$db->Execute(
				$sql=sprintf("
					UPDATE
						shop_user_shops
					SET
						%s
					WHERE
						id = %u
				"
					,implode(',', $sql)
					,$shop['id']
				)
			);
		else
			$db->Execute(
				sprintf("
					INSERT INTO
						shop_user_shops
					SET
						%s
				"
					,implode(',', $sql)
				)
			);
        DBCheck(3);
		//die($sql);
	}
	
	if(($password = trim($_POST['password'])) != '')
    {
		$db->Execute(
			sprintf("
				UPDATE
					shop_user_accounts
				SET
					password = %s
				WHERE
					id = %u
			"
				,$db->Quote($password)
				,$_POST['user_id']
			)
		);
        DBCheck(4);
    }

	//shipping addresses
	if(is_array(($_POST['delivery_name'])))
	{
		if(count(($_POST['delivery_name'])))
			$db->Execute(
				sprintf("
					DELETE FROM
						shop_user_addresses
					WHERE
						account_id=%u
					AND
						type = 'delivery'
					AND
						id NOT IN (%s)
				"
					,$_POST['user_id']
					,implode(', ', array_keys($_REQUEST['delivery_name']))
				)
			);
		else
			$db->Execute(
				sprintf("
					DELETE FROM
						shop_user_addresses
					WHERE
						account_id=%u
					AND
						type = 'delivery'
				"
					,$_POST['user_id']
				)
			);
		DBCheck(5);
		foreach($_POST['delivery_name'] as $delivery_id=>$unused)
        {
			$db->Execute(
				sprintf("
					UPDATE
						shop_user_addresses
					SET
						name = %s
						,email = %s
						,phone = %s
						,line1 = %s
						,line2 = %s
						,postcode = %s
						,country_id = %u
					WHERE
						id = %u
					AND
						account_id = %u
					AND
						type = 'delivery'
				"
					,$db->Quote($_POST['delivery_name'][$delivery_id])
					,$db->Quote($_POST['delivery_email'][$delivery_id])
					,$db->Quote($_POST['delivery_phone'][$delivery_id])
					,$db->Quote($_POST['delivery_line1'][$delivery_id])
					,$db->Quote($_POST['delivery_line2'][$delivery_id])
					,$db->Quote($_POST['delivery_postcode'][$delivery_id])
					,$_POST['delivery_country_id'][$delivery_id]
					,$delivery_id
					,$_POST['user_id']
				)
			);
            DBCheck(6);
        }
	} else {
		$db->Execute(
				sprintf("
					DELETE FROM
						shop_user_addresses
					WHERE
						account_id=%u
					AND
						type = 'delivery'
				"
					,$_POST['user_id']
				)
			);
        DBCheck(7);
	}
	
	if(is_array(($_POST['shipping_name'])))
	{
		foreach($_POST['shipping_name'] as $index=>$unused)
        {
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
					,$_POST['user_id']
					,$db->Quote($_POST['shipping_name'][$index])
					,$db->Quote($_POST['shipping_email'][$index])
					,$db->Quote($_POST['shipping_phone'][$index])
					,$db->Quote($_POST['shipping_line1'][$index])
					,$db->Quote($_POST['shipping_line2'][$index])
					,$db->Quote($_POST['shipping_postcode'][$index])
					,$_POST['shipping_country_id'][$index]
				)
			);
            DBCheck(8);
        }
	}
			
	//billing addresses
	if(is_array(($_POST['billing_address_name'])))
	{
		if(count(($_POST['billing_address_name'])))
			$db->Execute(
				sprintf("
					DELETE FROM
						shop_user_addresses
					WHERE
						account_id=%u
					AND
						type = 'billing'
					AND
						id NOT IN (%s)
				"
					,$_POST['user_id']
					,implode(', ', array_keys($_REQUEST['billing_address_name']))
				)
			);
		else
			$db->Execute(
				sprintf("
					DELETE FROM
						shop_user_addresses
					WHERE
						account_id=%u
					AND
						type = 'billing'
				"
					,$_POST['user_id']
				)
			);
		DBCheck(9);
		foreach($_POST['billing_address_name'] as $billing_id=>$unused)
        {
			$db->Execute(
				sprintf("
					UPDATE
						shop_user_addresses
					SET
						name = %s
						,email = %s
						,phone = %s
						,line1 = %s
						,line2 = %s
						,postcode = %s
						,country_id = %u
					WHERE
						id = %u
					AND
						account_id = %u
					AND
						type = 'billing'
				"
					,$db->Quote($_POST['billing_address_name'][$billing_id])
					,$db->Quote($_POST['billing_address_email'][$billing_id])
					,$db->Quote($_POST['billing_address_phone'][$billing_id])
					,$db->Quote($_POST['billing_address_line1'][$billing_id])
					,$db->Quote($_POST['billing_address_line2'][$billing_id])
					,$db->Quote($_POST['billing_address_postcode'][$billing_id])
					,$_POST['billing_address_country_id'][$billing_id]
					,$billing_id
					,$_POST['user_id']
				)
			);
            DBCheck(10);
        }
	} else {
		$db->Execute(
				sprintf("
					DELETE FROM
						shop_user_addresses
					WHERE
						account_id=%u
					AND
						type = 'billing'
				"
					,$_POST['user_id']
				)
			);
        DBCheck(11);
	}
	
	
	if(is_array(($_POST['billing_name'])))
	{
		foreach($_POST['billing_name'] as $index=>$unused)
        {
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
					,$_POST['user_id']
					,$db->Quote($_POST['billing_name'][$index])
					,$db->Quote($_POST['billing_email'][$index])
					,$db->Quote($_POST['billing_phone'][$index])
					,$db->Quote($_POST['billing_line1'][$index])
					,$db->Quote($_POST['billing_line2'][$index])
					,$db->Quote($_POST['billing_postcode'][$index])
					,$_POST['billing_country_id'][$index]
				)
			);
            DBCheck(12);
        }
	}	
	$ok=$db->CompleteTrans();
	if(!$ok)
		error("There was a problem whilst updating the user, please try again.  If this problem persists please contact your designated support contact","Database Error");
?>