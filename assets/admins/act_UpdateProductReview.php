<?
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();

	$db->Execute(
		sprintf("
			UPDATE
				shop_product_reviews
			SET
				rating = %u
				,title = %s
				,author = %s
				,description = %s
				,status = %s
			WHERE
				id = %u
		"
			,$_POST['rating']
			,$db->Quote($_POST['title'])
			,$db->Quote($_POST['author'])
			,$db->Quote($_POST['description'])
			,$db->Quote($_POST['status'])
			,$_REQUEST['review_id']
		)
	);

	$ok=$db->CompleteTrans();
	if(!$ok)
		error("There was a problem whilst updating the review, please try again.  If this problem persists please contact your designated support contact","Database Error");
?>