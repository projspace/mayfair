<?
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();

	//Get page details
	$page=$db->Execute(
		sprintf("
			SELECT
				revision
				,content_revision
			FROM
				cms_pages
			WHERE
				id=%u
			AND
				siteid=%u
		"
			,$_REQUEST['pageid']
			,$session->getValue("siteid")
		)
	);

	$db->Execute(
		sprintf("
			UPDATE
				cms_pages
			SET
				pendingedit=0
			WHERE
				id=%u
			AND
				siteid=%u
		"
			,$_REQUEST['pageid']
			,$session->getValue("siteid")
		)
	);

	//Create an audit row
	$db->Execute(
		sprintf("
			INSERT INTO
				cms_pages_audit (
					pageid
					,accountid
					,revision
					,content_revision
					,action
					,time
				) VALUES (
					%u
					,%u
					,%u
					,%u
					,%s
					,%s
				)
		"
			,$_REQUEST['pageid']
			,$session->account_id
			,$page->fields['revision']
			,$page->fields['content_revision']
			,$db->Quote("rejected edit")
			,$db->DBTimestamp(time())
		)
	);

	$ok=$db->CompleteTrans();
    if(!$ok)
		error("There was a problem whilst rejecting the page edit, please try again.  If this persists please notify your designated support contact","Database Error");
?>