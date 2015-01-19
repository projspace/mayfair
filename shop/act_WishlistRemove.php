<?
	$wish_id = $_REQUEST['wish_id']+0;
	
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();
	
	$db->Execute(
		sprintf("
			DELETE FROM
				shop_wishlist
			WHERE
				id=%u
		"
			,$wish_id
		)
	);
	
	$ok=$db->CompleteTrans();
?>
