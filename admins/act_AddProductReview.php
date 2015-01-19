<?
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();

	$db->Execute(
		sprintf("
			INSERT INTO
				shop_product_reviews
			SET
				product_id = %u
				,rating = %u
				,title = %s
				,author = %s
				,description = %s
				,posted = NOW()
				,status = 'approved'
		"
			,$_REQUEST['product_id']
			,$_POST['rating']
			,$db->Quote($_POST['title'])
			,$db->Quote($_POST['author'])
			,$db->Quote($_POST['description'])
		)
	);
	$review_id=$db->Insert_ID();

	$ok=$db->CompleteTrans();
	if(!$ok)
		error("There was a problem whilst adding the review, please try again.  If this problem persists please contact your designated support contact","Database Error");
?>