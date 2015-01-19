<?
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();

	$db->Execute(
		sprintf("
			UPDATE
				shop_meta_tags
			SET
				name = %s
				,description = %s
			WHERE
				id = %u
		"
			,$db->Quote($_POST['name'])
			,$db->Quote($_POST['description'])
			,$_POST['tag_id']
		)
	);

	$ok=$db->CompleteTrans();
	if(!$ok)
		error("There was a problem whilst updating the meta tag, please try again.  If this problem persists please contact your designated support contact","Database Error");
?>