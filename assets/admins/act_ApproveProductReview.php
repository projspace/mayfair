<?
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();

	$db->Execute(
		sprintf("
			UPDATE
				shop_product_reviews
			SET
				status = 'approved'
			WHERE
				id = %u
		"
			,$_REQUEST['review_id']
		)
	);

	$ok=$db->CompleteTrans();
	if(!$ok)
		error("There was a problem whilst approving the review, please try again.  If this problem persists please contact your designated support contact","Database Error");
?>