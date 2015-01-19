<?
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();
	
	$db->Execute(
		sprintf("
			INSERT INTO
				shop_suppliers
			SET
				name = %s
				,address = %s
				,postcode = %s
				,country_id = %u
				,tel = %s
				,fax = %s
				,email = %s
				,notes = %s
		"
			,$db->Quote($_POST['name'])
			,$db->Quote($_POST['address'])
			,$db->Quote($_POST['postcode'])
			,$_POST['country_id']
			,$db->Quote($_POST['tel'])
			,$db->Quote($_POST['fax'])
			,$db->Quote($_POST['email'])
			,$db->Quote($_POST['notes'])
		)
	);
	
	$ok=$db->CompleteTrans();
	if(!$ok)
    	error("There was a problem whilst adding the supplier, please try again.  If this persists please notify your designated support contact","Database Error");
?>