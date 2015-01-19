<?
	$validator=new Validation("summary");
	$validator->addRequired("type_id","Type of event");
	$validator->addRequired("name","Name of event");
	$validator->addRequired("date","Date of event");
?>