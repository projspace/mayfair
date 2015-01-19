<?
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();
	
	$db->Execute(
		sprintf("
			DELETE FROM
				shop_product_reviews
			WHERE
				id=%u
		"
			,$_POST['review_id']
		)
	);
	
	$ok=$db->CompleteTrans();
	if(!$ok)
		error("There was a problem whilst removing the review, please try again.  If this persists please notify your designated support contact","Database Error");
?>