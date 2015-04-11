<?
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();
	
	$db->Execute(
		sprintf("
			DELETE FROM
				shop_refs
			WHERE
				id=%u
			AND
				category_id=%u
		"
			,$_POST['referenceid']
			,$_POST['category_id']
		)
	);
	
	$ok=$db->CompleteTrans();
	if(!$ok)
    	error("There was a problem whilst removing the product reference, please try again.  If this persists please notify your designated support contact","Database Error");
?>