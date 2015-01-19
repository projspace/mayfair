<?
	$validator=new Validation("summary");

    $validator->addRequired("firstname","First name");
	$validator->addCustom("firstname","First name","checkName","First name must be at least ".(GIFT_NAME_MIN_LENGTH+0)." characters.");

    $validator->addRequired("lastname","Last name");
	$validator->addCustom("lastname","Last name","checkName","Last name must be at least ".(GIFT_NAME_MIN_LENGTH+0)." characters.");

    $validator->addRequired("phone","Phone");
	$validator->addRegex("phone","Phone",'^[0-9\(\)\[\]\. -]{'.(GIFT_PHONE_MIN_DIGITS+0).',}$',"Phone is not a valid phone number.","main","i");

	$validator->addRequired("email","Email");
	$validator->addRegex("email","Email","^[a-z0-9._-]+@[a-z0-9._-]+\.[a-z]+$","Please enter a valid email address.","main","i");
	$validator->addCustom("email","Email","existingUsername","This email already exists. Please choose another one.");
	
	$validator->addRequired("password", "Password");
	$validator->addRequired("repeat_password","Re-type password");
	$validator->addCompare("password","Password","repeat_password","Password check");
	$validator->addCustom("password","Password","checkPassword","Please enter a valid password.");

    function val_checkName($value)
	{
		$value = trim($value);
		if($value == '')
			return true;

		return strlen($value) >= (GIFT_NAME_MIN_LENGTH+0);
	}

	function val_checkPassword($value)
	{
		$value = trim($value);
		if($value == '')
			return true;
		
		return strlen($value) >= 6;
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