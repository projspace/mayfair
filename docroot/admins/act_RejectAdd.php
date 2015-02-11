<?
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();

	//Get the parent id
	$page=$db->Execute(
		sprintf("
			SELECT
				parent_id
				,ord
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
	DBCheck(1);

	//"Delete" the page
	$db->Execute(
		sprintf("
			UPDATE
				cms_pages
			SET
				deleted=1
			WHERE
				id=%u
			AND
				siteid=%u
		"
			,$_POST['pageid']
			,$session->getValue("siteid")
		)
	);
	DBCheck(2);

	//Defragment the ord variable
	$db->Execute(
		sprintf("
			UPDATE
				cms_pages
			SET
				ord=ord-1
			WHERE
				ord>%u
			AND
				parent_id=%u
			AND
				siteid=%u
		"
			,$page->fields['ord']
			,$page->fields['parent_id']
			,$session->getValue("siteid")
		)
	);
	DBCheck(3);

	//Get the maximum order
	$max=$db->Execute(
		sprintf("
			SELECT
				MAX(ord) AS max
			FROM
				cms_pages
			WHERE
				parent_id=%u
			AND
				siteid=%u
			AND
				deleted=0
		"
			,$page->fields['parent_id']
			,$session->getValue("siteid")
		)
	);
	DBCheck(4);

	//Move child pages up to this level
	$db->Execute(
		sprintf("
			UPDATE
				cms_pages
			SET
				parent_id=%u
				,ord=ord+%u
			WHERE
				parent_id=%u
			AND
				siteid=%u
		"
			,$page->fields['parent_id']
			,$max->fields['max']
			,$_POST['pageid']
			,$session->getValue("siteid")
		)
	);
	DBCheck(5);

	$mptt->removePage($_POST['pageid']);

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
			,$_POST['pageid']
			,$session->account_id
			,1
			,1
			,$db->Quote("rejected addition")
			,$db->DBTimestamp(time())
		)
	);
	DBCheck(6);

	$ok=$db->CompleteTrans();
    if(!$ok)
		error("There was a problem whilst rejecting the page addition, please try again.  If this persists please notify your designated support contact","Database Error");
?>