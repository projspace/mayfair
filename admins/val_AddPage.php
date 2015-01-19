<?
	$validator=new Validation("summary");
	$validator->addRequired("name","Page Name");
	$validator->addRegex("valid_from","Valid From","/[0-9]{2}\/[0-9]{2}\/[0-9]{4}/","Valid From must be formatted as dd/mm/yyyy");
	$validator->addRegex("valid_to","Valid To","/[0-9]{2}\/[0-9]{2}\/[0-9]{4}/","Valid To must be formatted as dd/mm/yyyy");
?>