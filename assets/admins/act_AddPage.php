<?
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();

	/**
	 * Get and insert the data for the page
	 */

	//Get maximum order variable for insertion
	$ord=$db->Execute(
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
			,$_POST['parent_id']
			,$session->getValue("siteid")
		)
	);

	//Insert the bulk of the data
	
	$valid_from = ($_POST['valid_from'] != '')?implode('-', array_reverse(explode('/', $_POST['valid_from']))):'';
	$valid_to = ($_POST['valid_to'] != '')?implode('-', array_reverse(explode('/', $_POST['valid_to']))):'';
	
	$db->Execute(
		sprintf("
			INSERT INTO
				cms_pages (
					siteid
					,revision
					,content_revision
					,parent_id
					,layoutid
					,ord
					,valid_from
					,valid_to
					,pagetype
					,name
					,page
					,pendingadd
					,hidden
					,sidebar
					,megafooter
					,footer
					,menu
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
					,%s
					,%u
					,%u
					,%u
					,%u
					,%u
					,%u
				)
		"
			,$session->getValue("siteid")
			,1
			,1
			,$_POST['parent_id']
			,$_POST['layoutid']
			,$ord->fields['max']+1
			,$db->DBDate($valid_from)
			,$db->DBDate($valid_to)
			,$_POST['pagetype']
			,$db->Quote($_POST['name'])
			,$db->Quote(name2page($_POST['name']))
			,1
			,$_POST['hidden']
			,$_POST['sidebar']
			,$_POST['megafooter']
			,$_POST['footer']
			,$_POST['menu']
		)
	);

	$pageid=$db->Insert_ID();

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
					,accepted
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
					,%u
				)
		"
			,$pageid
			,$session->getValue("siteid")
			,1
			,1
			,$_POST['parent_id']
			,$_POST['layoutid']
			,$db->DBDate($_POST['valid_from'])
			,$db->DBDate($_POST['valid_to'])
			,$_POST['pagetype']
			,$db->Quote($_POST['name'])
			,$session->account_id
			,$db->DBTimestamp(time())
			,1
		)
	);

	$mptt->addPage($pageid,$_POST['parent_id']);

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
			,$pageid
			,$session->account_id
			,1
			,1
			,$db->Quote("addition")
			,$db->DBTimestamp(time())
		)
	);

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
			,$pageid
			,1
			,$db->Quote($_POST['content'][0])
			,$db->Quote($_POST['content'][1])
			,$db->Quote(serialize(array_stripslashes($_POST['custom'])))
			,$db->Quote($_POST['meta_title'])
			,$db->Quote($_POST['meta_keywords'])
			,$db->Quote($_POST['meta_description'])
		)
	);

	$ok=$db->CompleteTrans();
	if(!$ok)
    	error("There was a problem whilst adding the page, please try again.  If this persists please notify your designated support contact","Database Error");
	else
	{
		$search=new Search($config);
		$search->add("page",$pageid,$_POST['name'],strip_tags($_POST['content'][0]),array('description'=>strip_tags($_POST['content'][1])));
	}
?>
