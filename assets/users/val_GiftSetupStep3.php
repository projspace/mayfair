<?
	$validator=new Validation("summary");
	$validator->addRequired("address1","Address 1");
	$validator->addRequired("postcode","Zip code");
	$validator->addRequired("country_id","State");
	$validator->addRequired("area_id","Country");
	$validator->addRequired("delivery_after","Deliver after");
?>