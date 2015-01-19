<?
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();

	$db->Execute(
		sprintf("
			UPDATE
				shop_product_reviews
			SET
				status = 'rejected'
			WHERE
				id = %u
		"
			,$_REQUEST['review_id']
		)
	);

	$ok=$db->CompleteTrans();
	if(!$ok)
		error("There was a problem whilst rejecting the review, please try again.  If this problem persists please contact your designated support contact","Database Error");
?>