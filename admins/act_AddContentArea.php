<?
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();
	
	$db->Execute(
		sprintf("
			INSERT INTO
				cms_content_areas
			SET
				name = %s
				,description = %s
		"
			,$db->Quote($_POST['name'])
			,$db->Quote($_POST['content'][0])
		)
	);
	$area_id=$db->Insert_ID();
	
	$ok=$db->CompleteTrans();
	if(!$ok)
    	error("There was a problem whilst adding the content, please try again.  If this persists please notify your designated support contact","Database Error");
?>