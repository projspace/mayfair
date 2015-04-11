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
	DBCheck(1);

	$revision=$rev->fields['revision']+1;
	$content_revision=$rev->fields['content_revision']+1;

	/**
	 * Get current page values
	 */
	$page=$db->Execute(
		sprintf("
			SELECT
				parent_id
				,layoutid
				,pagetype
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
	DBCheck(2);

	/**
	 * Insert the page data
	 */
	 
	$valid_from = ($_POST['valid_from'] != '')?implode('-', array_reverse(explode('/', $_POST['valid_from']))):'';
	$valid_to = ($_POST['valid_to'] != '')?implode('-', array_reverse(explode('/', $_POST['valid_to']))):'';
	 
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
					,%s
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
			,$page->fields['layoutid']
			,$db->DBDate($valid_from)
			,$db->DBDate($valid_to)
			,$_POST['pagetype']
			,$db->Quote($_POST['name'])
			,$session->account_id
			,$db->DBTimestamp(time())
		)
	);
	DBCheck(3);

	/**
	 * Update the page state
	 */
	$db->Execute(
		sprintf("
			UPDATE
				cms_pages
			SET
				pendingedit=%u
				,hidden=%u
				,sidebar=%u
				,megafooter=%u
				,footer=%u
				,menu=%u
				,pagetype=%u
			WHERE
				id=%u
			AND
				siteid=%u
		"
			,1
			,$_POST['hidden']
			,$_POST['sidebar']
			,$_POST['megafooter']
			,$_POST['footer']
			,$_POST['menu']
			,$_POST['pagetype']
			,$_POST['pageid']
			,$session->getValue("siteid")
		)
	);
	DBCheck(4);

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
			,$db->Quote("edit")
			,$db->DBTimestamp(time())
		)
	);
	DBCheck(5);

	/**
	 * Insert the content
	 */
	$db->Execute(
		sprintf("
			INSERT INTO
				cms_content (
					pageid
					,revision
					,content
					,description
					,custom
					,meta_title
					,meta_keywords
					,meta_description
				) VALUES (
					%u
					,%u
					,%s
					,%s
					,%s
					,%s
					,%s
					,%s
				)
		"
			,$_POST['pageid']
			,$content_revision
			,$db->Quote($_POST['content'][0])
			,$db->Quote($_POST['content'][1])
			,$db->Quote(serialize(array_stripslashes($_POST['custom'])))
			,$db->Quote($_POST['meta_title'])
			,$db->Quote($_POST['meta_keywords'])
			,$db->Quote($_POST['meta_description'])
		)
	);
	DBCheck(6);

	$ok=$db->CompleteTrans();
    if(!$ok)
    	error("There was a problem whilst updating the page, please try again.  If this persists please notify your designated support contact","Database Error");
	else
	{
		$search=new Search($config);
		$search->update("page",$_POST['pageid']+0,$_POST['name'],strip_tags($_POST['content'][0]),array('description'=>strip_tags($_POST['content'][1])));
	}
?>
