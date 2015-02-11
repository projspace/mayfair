<?
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();
	
	$db->Execute(
		sprintf("
			INSERT INTO
				shop_refs
				(
					product_id
					,category_id
				)
			VALUES
				(
					%u
					,%u
				)
		"
			,$_POST['product_id']
			,$_POST['category_id']
		)
	);
	
	$ok=$db->CompleteTrans();
	if(!$ok)
    	error("There was a problem whilst adding the product reference, please try again.  If this persists please notify your designated support contact","Database Error");
?>