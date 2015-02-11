<?
	$validator=new Validation("summary");
	$validator->addRequired("name", "Name");
	$validator->addRequired("email","Email");
	$validator->addRequired("phone","Phone");
	$validator->addRequired("country_id","Country");
	$validator->addRequired("line1","Line 1");
	$validator->addRequired("postcode","Postcode");
	
	$validator->addRegex("email","Email","^[a-z0-9._-]+@[a-z0-9._-]+\.[a-z]+$","Please enter a valid email address.","main","i");
?>