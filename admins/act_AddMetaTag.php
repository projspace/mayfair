<?
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();

	$db->Execute(
		sprintf("
			INSERT INTO
				shop_meta_tags (
					name
					,description
				) VALUES (
					%s
					,%s
				)
		"
			,$db->Quote($_POST['name'])
			,$db->Quote($_POST['description'])
		)
	);
	$tag_id=$db->Insert_ID();

	$ok=$db->CompleteTrans();
	if(!$ok)
		error("There was a problem whilst adding the meta tag, please try again.  If this problem persists please contact your designated support contact","Database Error");
?>