<?
	$validator=new Validation("summary");
	$validator->addRequired("email","Email");
	$validator->addRegex("email","Email","^[a-z0-9._-]+@[a-z0-9._-]+\.[a-z]+$","Please enter a valid email address.","main","i");
?>