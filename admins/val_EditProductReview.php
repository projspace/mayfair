<?
	$validator=new Validation("summary");
	$validator->addRequired("title","Title");
	$validator->addRequired("author","Author");
	$validator->addRequired("description","Description");
?>