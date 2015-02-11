<?
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();

	//Get current page details
	$page=$db->Execute(
		sprintf("
			SELECT
				ord
				,parent_id
				,revision
				,content_revision
				,lft
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

	//Get the new parent details
	$parent=$db->Execute(
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
			,$_POST['parent_id']
			,$session->getValue("siteid")
		)
	);

	echo $parent->fields['lft'].">=".$page->fields['lft']." && ".$parent->fields['rgt']."<=".$page->fields['rgt']."<br />";
	if($parent->fields['lft']>=$page->fields['lft'] && $parent->fields['rgt']<=$page->fields['rgt'])
	{
		error("You cannot move this page here","Move Error");
		$db->FailTrans();
	}

	//Get the new parent max order
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
			,$_POST['parent_id']
			,$session->getValue("siteid")
		)
	);

	//Defragment the order on the current category
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

	//"Move" the page
	$db->Execute(
		sprintf("
			UPDATE
				cms_pages
			SET
				parent_id=%u
				,ord=%u
			WHERE
				id=%u
			AND
				siteid=%u
		"
			,$_POST['parent_id']
			,$max->fields['max']+1
			,$_POST['pageid']
			,$session->getValue("siteid")
		)
	);

	//"Move" all old versions
	$db->Execute(
		sprintf("
			UPDATE
				cms_pages_log
			SET
				parent_id=%u
			WHERE
				pageid=%u
			AND
				siteid=%u
		"
			,$_POST['parent_id']
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
			,$page->fields['revision']
			,$page->fields['content_revision']
			,$db->Quote("move page")
			,$db->DBTimestamp(time())
		)
	);

	//Perform the MPTT update
	$mptt->rebuildTree(0,0);

	/**
	 * Take care of updating the .htaccess file
	 */

	//Get the left and right values of this page
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

	//Get the path of the current site (for opening the correct htaccess file)
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

	//Get all pages that are affected
	$pages=$db->Execute(
		sprintf("
			SELECT
				id
				,lft
				,rgt
			FROM
				cms_pages
			WHERE
				lft>=%u
			AND
				rgt<=%u
			AND
				siteid=%u
			AND
				deleted=0
		"
			,$page->fields['lft']
			,$page->fields['rgt']
			,$session->getValue("siteid")
		)
	);

	while($row=$pages->FetchRow())
	{
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
				,$row['lft']
				,$row['rgt']
				,$session->getValue("siteid")
			)
		);
		$trailarr=array();

		while($trow=$trail->FetchRow())
		{
			//if($trow['lft']>1)
				$trailarr[]=name2page($trow['name']);
		}
		$hta->updatePage($trailarr,$row['id']);

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
				,$row['id']
				,$session->getValue("siteid")
			)
		);
	}

	$ok=$db->CompleteTrans();
	if($ok)
		$hta->save();
	else
		error("There was a problem whilst moving the page, please try again.  If this persists please notify your designated support contact","Database Error");
?>