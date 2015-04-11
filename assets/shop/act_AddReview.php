<?
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();

	$db->Execute(
		$sql = sprintf("
			INSERT INTO
				shop_product_reviews
			SET
				product_id = %u
				,rating = %u
				,title = %s
				,author = %s
				,description = %s
				,posted = NOW()
		"
			,safe($_REQUEST['product_id'])
			,safe($_POST['stars'])
			,$db->Quote(safe($_POST['title']))
			,$db->Quote(safe($_POST['author']))
			,$db->Quote(safe($_POST['description']))
		)
	);
	$review_id=$db->Insert_ID();

	$ok=$db->CompleteTrans();
?>