<?
	$validator=new Validation("summary");
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
?>