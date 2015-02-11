<?
	$validator=new Validation("summary");
	$validator->addRequired("email","Email");
	$validator->addRequired("password","Specify New Password");
	$validator->addCompare("password","Specify New Password","confirm","Confirm");
?>