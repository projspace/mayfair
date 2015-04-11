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
			,$_POST['pageid']
			,$session->getValue("siteid")
		)
	);

	//Reject removal
	$db->Execute(
		sprintf("
			UPDATE
				cms_pages
			SET
				pendingremove=0
			WHERE
				id=%u
			AND
				siteid=%u
		"
			,$_POST['pageid']
			,$session->getValue("siteid")
		)
	);

	//Create audit log
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
			,$_POST['pageid']
			,$session->account_id
			,$page->fields['revision']
			,$page->fields['content_revision']
			,$db->Quote("rejected removal")
			,$db->DBTimeStamp(time())
		)
	);

	$ok=$db->CompleteTrans();
    if(!$ok)
		error("There was a problem whilst rejecting the page removal, please try again.  If this persists please notify your designated support contact","Database Error");
?>