<?
	$validator=new Validation("summary");
	$validator->addRequired("email","Email");
	$validator->addRegex("email","Email","^[a-z0-9._-]+@[a-z0-9._-]+\.[a-z]+$","Please enter a valid email address.","main","i");
	$validator->addCustom("email","Email","existingUsername","This email already exists. Please choose another one.");
	
	$validator->addRequired("password", "Password");
	$validator->addRequired("repeat_password","Re-type password");
	$validator->addCompare("password","Password","repeat_password","Password check");
	$validator->addCustom("password","Password","checkPassword","Please enter a valid password.");
	
	function val_checkPassword($value)
	{
		$value = trim($value);
		if($value == '')
			return true;
		
		return strlen($value) >= 6;
	}
	
	function val_existingUsername($value)
	{
		global $db, $user_session;
		
		$ret = $db->Execute(
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
				,$db->Quote($value)
				,$user_session->account_id
			)
		);
		
		return $ret->FetchRow()?false:true;
	}
?>