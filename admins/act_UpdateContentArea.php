<?
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();
	
	$db->Execute(
		sprintf("
			UPDATE
				cms_content_areas
			SET
				name = %s
				,description = %s
			WHERE
				id = %u
		"
			,$db->Quote($_POST['name'])
			,$db->Quote($_POST['content'][0])
			,$_REQUEST['area_id']
		)
	);
	
	$ok=$db->CompleteTrans();
	if(!$ok)
    	error("There was a problem whilst updating the content, please try again.  If this persists please notify your designated support contact","Database Error");
?>