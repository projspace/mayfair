<?
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();

	$db->Execute(
		sprintf("
			INSERT INTO
				cms_layouts (
					siteid
					,filename
					,name
					,description
					,sections
					,def
				) VALUES (
					%u
					,%s
					,%s
					,%s
					,%s
					,%u
				)
		"
			,$session->getValue("siteid")
			,$db->Quote(str_replace("..","",$_POST['filename']))
			,$db->Quote($_POST['name'])
			,$db->Quote($_POST['description'])
			,$db->Quote(str_replace("\n\n","\n",str_replace("\r","\n",trim($_POST['sections']))))
			,($_POST['default']=="on") ? 1 : 0
		)
	);
	$layoutid=$db->Insert_ID();
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
				,$layoutid
				,$session->getValue("siteid")
			)
		);
	}
	DBCheck(2);

	$ok=$db->CompleteTrans();
	if(!$ok)
		error("There was a problem whilst adding the layout file, please try again.  If this problem persists please contact your designated support contact","Database Error");
?>