<?
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();

	$db->Execute(
		sprintf("
			UPDATE
				gift_types
			SET
				name = %s
			WHERE
				id = %u
		"
			,$db->Quote(safe($_POST['name']))
			,$_REQUEST['type_id']
		)
	);

	$ok=$db->CompleteTrans();
	if(!$ok)
		error("There was a problem whilst updating the gift registry type, please try again.  If this problem persists please contact your designated support contact","Database Error");
?>