<?
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();

	$db->Execute(
		sprintf("
			UPDATE
				cms_variables
			SET
				value=%s
			WHERE
				name = '404_content'
		"
			,$db->Quote($_POST['content'][0])
		)
	);
	
	$db->Execute(
		sprintf("
			UPDATE
				cms_variables
			SET
				value=%s
			WHERE
				name = '404_title'
		"
			,$db->Quote($_POST['meta_title'])
		)
	);
	
	$db->Execute(
		sprintf("
			UPDATE
				cms_variables
			SET
				value=%s
			WHERE
				name = '404_keywords'
		"
			,$db->Quote($_POST['meta_keywords'])
		)
	);
	
	$db->Execute(
		sprintf("
			UPDATE
				cms_variables
			SET
				value=%s
			WHERE
				name = '404_description'
		"
			,$db->Quote($_POST['meta_description'])
		)
	);

	$ok=$db->CompleteTrans();
	if(!$ok)
		error("There was a problem whilst updating the 404 page, please try again.  If this problem persists please contact your designated support contact","Database Error");
?>