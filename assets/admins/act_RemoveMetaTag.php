<?
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();
	
	$db->Execute(
		sprintf("
			DELETE FROM
				shop_meta_tags
			WHERE
				id=%u
		"
			,$_POST['tag_id']
		)
	);
	
	$ok=$db->CompleteTrans();
	if(!$ok)
		error("There was a problem whilst removing the meta tag, please try again.  If this persists please notify your designated support contact","Database Error");
?>