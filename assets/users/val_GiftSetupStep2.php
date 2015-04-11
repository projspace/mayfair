<?
	$validator=new Validation("summary");
	$validator->addRequired("title","Title");
	$validator->addRequired("first_name","First name");
	$validator->addCustom("first_name","First name","checkName","First name must be at least ".(GIFT_NAME_MIN_LENGTH+0)." characters.");
	//$validator->addRequired("middle_name","Middle name");
	$validator->addCustom("middle_name","Middle name","checkName","Middle name must be at least ".(GIFT_NAME_MIN_LENGTH+0)." characters.");
	$validator->addRequired("surname","Surname");
	$validator->addCustom("surname","Surname","checkName","Surname must be at least ".(GIFT_NAME_MIN_LENGTH+0)." characters.");
	$validator->addRequired("primary_phone","Primary phone");
	$validator->addRegex("primary_phone","Primary phone","^[+]?[0-9]{".(GIFT_PHONE_MIN_DIGITS+0).",}$","Primary phone must have at least ".(GIFT_PHONE_MIN_DIGITS+0)." digits.","main","i");
	//$validator->addRequired("secondary_phone","Secondary phone");
	$validator->addRegex("secondary_phone","Secondary phone","^[+]?[0-9]{".(GIFT_PHONE_MIN_DIGITS+0).",}$","Secondary phone must have at least ".(GIFT_PHONE_MIN_DIGITS+0)." digits.","main","i");
	$validator->addRequired("contact_method","Preferred method");
	
	$validator->addRequired("email","Email");
	$validator->addRegex("email","Email","^[a-z0-9._-]+@[a-z0-9._-]+\.[a-z]+$","Please enter a valid email address.","main","i");
	$validator->addRequired("confirm_email","Confirm Email");
	$validator->addCompare("email","Email","confirm_email","Confirm Email");
	$validator->addCustom("email","Email","existingUsername","This email already exists. Please choose another one.");
	
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