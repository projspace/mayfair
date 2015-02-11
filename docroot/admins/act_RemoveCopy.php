<?
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();
	
	$db->Execute(
		sprintf("
			DELETE FROM
				shop_products
			WHERE
				id=%u
			AND
				parent_id>0
		"
			,$_POST['product_id']
		)
	);
	
	$ok=$db->CompleteTrans();
	if(!$ok)
    	error("There was a problem whilst removing the product copy, please try again.  If this persists please notify your designated support contact","Database Error");
?>