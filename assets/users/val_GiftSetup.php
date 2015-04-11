<?
	$validator=new Validation("summary");
	$validator->addRequired("type_id","Type of event");
    if($_REQUEST['type_id']+0 == 4)
            $validator->addRequired("other_type","Type");
	$validator->addRequired("name","Name of event");
	$validator->addRequired("date","Date of event");

    $validator->addRequired("title","Title");
	$validator->addRequired("first_name","First name");
	$validator->addCustom("first_name","First name","checkName","First name must be at least ".(GIFT_NAME_MIN_LENGTH+0)." characters.");
	//$validator->addRequired("middle_name","Middle name");
	$validator->addCustom("middle_name","Middle name","checkName","Middle name must be at least ".(GIFT_NAME_MIN_LENGTH+0)." characters.");
	$validator->addRequired("surname","Surname");
	$validator->addCustom("surname","Surname","checkName","Surname must be at least ".(GIFT_NAME_MIN_LENGTH+0)." characters.");
	$validator->addRequired("primary_phone","Primary phone");
	//$validator->addRegex("primary_phone","Primary phone","^[+]?[0-9]{".(GIFT_PHONE_MIN_DIGITS+0).",}$","Primary phone must have at least ".(GIFT_PHONE_MIN_DIGITS+0)." digits.","main","i");
	$validator->addRegex("primary_phone","Primary phone",'^[0-9\(\)\[\]\. -]{'.(GIFT_PHONE_MIN_DIGITS+0).',}$',"Primary phone is not a valid phone number.","main","i");
	//$validator->addRequired("secondary_phone","Secondary phone");
	//$validator->addRegex("secondary_phone","Secondary phone","^[+]?[0-9]{".(GIFT_PHONE_MIN_DIGITS+0).",}$","Secondary phone must have at least ".(GIFT_PHONE_MIN_DIGITS+0)." digits.","main","i");
    $validator->addRegex("secondary_phone","Secondary phone",'^[0-9\(\)\[\]\. -]{'.(GIFT_PHONE_MIN_DIGITS+0).',}$',"Secondary phone is not a valid phone number.","main","i");
	$validator->addRequired("contact_method","Preferred method");

	$validator->addRequired("email","Email");
	$validator->addRegex("email","Email","^[a-z0-9._-]+@[a-z0-9._-]+\.[a-z]+$","Please enter a valid email address.","main","i");
	$validator->addRequired("confirm_email","Confirm Email");
	$validator->addCompare("email","Email","confirm_email","Confirm Email");
	$validator->addCustom("email","Email","existingUsername","This email already exists. Please choose another one.");

    $validator->addRequired("address1","Address 1");
    $validator->addRequired("address4","Address 4");
	$validator->addRequired("postcode","Zip code");
	$validator->addRequired("country_id","State");
	$validator->addRequired("area_id","Country");
	$validator->addRequired("delivery_after","Deliver after");

    $validator->addRequired("public","Public list");
	$validator->addRequired("terms","Terms & Conditions");
	$validator->addRequired("password","Password");
	$validator->addRequired("confirm_password","Confirm Password");
	$validator->addCompare("password","Password","confirm_password","Confirm Password");
	$validator->addCustom("password","Password","checkPassword","Password must be at least 3 characters.");
	$validator->addCustom("confirm_password","Confirm Password","checkPassword","Confirm Password must be at least 3 characters.");

	function val_checkPassword($value)
	{
		$value = trim($value);
		if($value == '')
			return true;

		return strlen($value) >= 3;
	}

	function val_checkName($value)
	{
		$value = trim($value);
		if($value == '')
			return true;

		return strlen($value) >= (GIFT_NAME_MIN_LENGTH+0);
	}

	function val_existingUsername($value)
	{
		global $db;

		$ret = $db->Execute(
			sprintf("
				SELECT
					id
				FROM
					shop_user_accounts
				WHERE
					email = %s
			"
				,$db->Quote($value)
			)
		);

		return $ret->FetchRow()?false:true;
	}
?>