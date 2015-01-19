<?
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();
	
	$db->Execute(
		sprintf("
			DELETE FROM
				shop_product_similar
			WHERE
				product_id=%u
			AND
				similar_product_id=%u
		"
			,$_POST['product_id']
			,$_POST['similar_product_id']
		)
	);
	
	$ok=$db->CompleteTrans();
	if(!$ok)
		error("There was a problem whilst removing the product, please try again.  If this persists please notify your designated support contact","Database Error");
?>