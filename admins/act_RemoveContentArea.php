<?
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();
	
	$db->Execute(
		sprintf("
			DELETE FROM
				cms_content_areas
			WHERE
				id=%u
		"
			,$_POST['area_id']
		)
	);
	
	$ok=$db->CompleteTrans();
	if(!$ok)
		error("There was a problem whilst removing the content, please try again.  If this persists please notify your designated support contact","Database Error");
?>