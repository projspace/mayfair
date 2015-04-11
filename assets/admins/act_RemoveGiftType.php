<?
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();
	
	$db->Execute(
		sprintf("
			DELETE FROM
				gift_types
			WHERE
				id=%u
		"
			,$_POST['type_id']
		)
	);
	
	$ok=$db->CompleteTrans();
	if(!$ok)
		error("There was a problem whilst removing the gift registry type, please try again.  If this persists please notify your designated support contact","Database Error");
?>