<?
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();

	$db->Execute(
		sprintf("
			UPDATE
				cms_layouts
			SET
				filename=%s
				,name=%s
				,description=%s
				,sections=%s
				,def=%u
			WHERE
				id=%u
			AND
				siteid=%u
		"
			,$db->Quote(str_replace("..","",$_POST['filename']))
			,$db->Quote($_POST['name'])
			,$db->Quote($_POST['description'])
			,$db->Quote(str_replace("\n\n","\n",str_replace("\r","\n",trim($_POST['sections']))))
			,($_POST['default']=="on") ? 1 : 0
			,$_POST['layoutid']
			,$session->getValue("siteid")
		)
	);
	DBCheck(1);

	if($_POST['default']=="on")
	{
		$db->Execute(
			sprintf("
				UPDATE
					cms_layouts
				SET
					def=0
				WHERE
					def=1
				AND
					id!=%u
				AND
					siteid=%u
			"
				,$_POST['layoutid']
				,$session->getValue("siteid")
			)
		);
	}
	DBCheck(2);

	$ok=$db->CompleteTrans();
	if(!$ok)
		error("There was a problem whilst updating the layout file, please try again.  If this problem persists please contact your designated support contact","Database Error");
?>