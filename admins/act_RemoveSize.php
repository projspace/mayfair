<?
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();
	
	$db->Execute(
		sprintf("
			DELETE FROM
				shop_sizes
			WHERE
				id=%u
		"
			,$_POST['size_id']
		)
	);
	
	$ok=$db->CompleteTrans();
	if(!$ok)
		error("There was a problem whilst removing the product size, please try again.  If this persists please notify your designated support contact","Database Error");
?>