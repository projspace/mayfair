<?
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();

	//Get maximum order under top
	$ord=$db->Execute(
		sprintf("
			SELECT
				MAX(ord) AS max
			FROM
				cms_pages
			WHERE
				parent_id=0
			AND
				siteid=%u
			AND
				deleted=0
		"
			,$session->getValue("siteid")
		)
	);

	//"Undelete" the page
	$db->Execute(
		sprintf("
			UPDATE
				cms_pages
			SET
				deleted=%u
				,parent_id=%u
				,ord=%u
			WHERE
				deleted=%u
			AND
				id=%u
			AND
				siteid=%u
		"
			,0
			,0
			,$ord->fields['max']+1
			,1
			,$_POST['pageid']
			,$session->getValue("siteid")
		)
	);

	$mptt->rebuildTree(0,0);

	//Get page details
	$page=$db->Execute(
		sprintf("
			SELECT
				*
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
			,$page->fields['revisision']
			,$page->fields['content_revision']
			,$db->Quote("undelete")
			,$db->DBTimestamp(time())
		)
	);

	/**
	 * Take care of updating the .htaccess file and url links in page table
	 */

	$page=$db->Execute(
		sprintf("
			SELECT
				lft
				,rgt
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

	$site=$db->Execute(
		sprintf("
			SELECT
				path
			FROM
				cms_sites
			WHERE
				id=%u
		"
			,$session->getValue("siteid")
		)
	);

	$hta=new HTAccess($config,$config['path']);

	$trail=$db->Execute(
		sprintf("
			SELECT
				id
				,name
				,lft
			FROM
				cms_pages
			WHERE
				lft<=%u
			AND
				rgt>=%u
			AND
				siteid=%u
			AND
				deleted=0
			ORDER BY
				lft
			ASC
		"
			,$page->fields['lft']
			,$page->fields['rgt']
			,$session->getValue("siteid")
		)
	);

	$trailarr=array();

	while($trow=$trail->FetchRow())
	{
		//if($trow['lft']>1)
			$trailarr[]=name2page($trow['name']);
	}

	$hta->addPage($trailarr,$_POST['pageid']);

	$db->Execute(
		sprintf("
			UPDATE
				cms_pages
			SET
				url=%s
			WHERE
				id=%u
			AND
				siteid=%u
		"
			,$db->Quote(implode("/",$trailarr))
			,$_POST['pageid']
			,$session->getValue("siteid")
		)
	);


    $ok=$db->CompleteTrans();
    if($ok)
		$hta->save();
    else
    	error("There was a problem whilst undeleting the page, please try again.  If this persists please notify your designated support contact","Database Error");
?>