<?
	$validator=new Validation("summary");
	$validator->addRequired("email","Email");
	$validator->addCompare("password","Specify New Password","confirm","Confirm");
?>