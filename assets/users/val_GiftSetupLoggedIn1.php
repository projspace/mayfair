<?
	$validator=new Validation("summary");
	$validator->addRequired("type_id","Type of event");
    if($_REQUEST['type_id']+0 == 4)
        $validator->addRequired("other_type","Type");
	$validator->addRequired("name","Name of event");
	$validator->addRequired("date","Date of event");
	
	$validator->addRequired("address1","Address 1");
    $validator->addRequired("address4","Address 4");
	$validator->addRequired("postcode","Zip code");
	$validator->addRequired("country_id","State");
	$validator->addRequired("area_id","Country");
	$validator->addRequired("delivery_after","Deliver after");
	
	$validator->addRequired("public","Public list");
	$validator->addRequired("terms","Terms & Conditions");
?>