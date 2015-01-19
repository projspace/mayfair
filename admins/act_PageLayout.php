<?
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();

	/**
	 * Get the page revision
	 */
	$rev=$db->Execute(
		sprintf("
			SELECT
				revision
				,content_revision
			FROM
				cms_pages_log
			WHERE
				pageid=%u
			AND
				siteid=%u
			ORDER BY
				revision DESC
			LIMIT 1
		"
			,$_POST['pageid']
			,$session->getValue("siteid")
		)
	);

	$revision=$rev->fields['revision']+1;
	$content_revision=$rev->fields['content_revision'];

	/**
	 * Get current page values
	 */
	$page=$db->Execute(
		sprintf("
			SELECT
				parent_id
				,valid_from
				,valid_to
				,pagetype
				,name
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

	/**
	 * Insert the page data
	 */
	$db->Execute(
		sprintf("
			INSERT INTO
				cms_pages_log (
					pageid
					,siteid
					,revision
					,content_revision
					,parent_id
					,layoutid
					,valid_from
					,valid_to
					,pagetype
					,name
					,accountid
					,time
				) VALUES (
					%u
					,%u
					,%u
					,%u
					,%u
					,%u
					,%s
					,%s
					,%u
					,%s
					,%u
					,%s
				)
		"
			,$_POST['pageid']
			,$session->getValue("siteid")
			,$revision
			,$content_revision
			,$page->fields['parent_id']
			,$_POST['layoutid']
			,$db->DBDate($_POST['valid_from'])
			,$db->DBDate($_POST['valid_to'])
			,$page->fields['pagetype']
			,$db->Quote($page->fields['name'])
			,$session->account_id
			,$db->DBTimestamp(time())
		)
	);

	/**
	 * Update the page state
	 */
	$db->Execute(
		sprintf("
			UPDATE
				cms_pages
			SET
				pendingedit=%u
			WHERE
				id=%u
			AND
				siteid=%u
		"
			,1
			,$_POST['pageid']
			,$session->getValue("siteid")
		)
	);

	/**
	 * Create an audit log entry
	 */
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
			,$revision
			,$content_revision
			,$db->Quote("edit - change layout")
			,$db->DBTimestamp(time())
		)
	);

	$ok=$db->CompleteTrans();
    if(!$ok)
		error("There was a problem whilst performing the layout change, please try again.  If this persists please notify your designated support contact","Database Error");
?>