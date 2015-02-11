<?
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();

	//Get page details
	$page=$db->Execute(
		sprintf("
			SELECT
				revision
				,content_revision
				,parent_id
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

	//"Delete" the page
	$db->Execute(
		sprintf("
			UPDATE
				cms_pages
			SET
				pendingremove=0
				,deleted=1
			WHERE
				pendingremove=1
			AND
				id=%u
			AND
				siteid=%u
		"
			,$_POST['pageid']
			,$session->getValue("siteid")
		)
	);

	//Defragment the order field
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

	$mptt->removePage($_POST['pageid']);
	$mptt->rebuildTree(0,0);

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
			,$db->Quote("approved removal")
			,$db->DBTimestamp(time())
		)
	);

	// Take care of updating the .htaccess file
	$hta=new HTAccess($config,$config['path']);

	//Get the left and right values of the parent page
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
			,$page->fields['parent_id']
			,$session->getValue("siteid")
		)
	);

	if($parent->RecordCount()==0)
	{
		//Parent page doesn't exist (e.g. topmost page has just been deleted - edge case!)
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
					siteid=%u
				AND
					deleted=0
				ORDER BY
					lft
				ASC
			"
				,$session->getValue("siteid")
			)
		);
	}
	else
	{
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
				ORDER BY
					lft
				ASC
			"
				,$parent->fields['lft']
				,$parent->fields['rgt']
				,$session->getValue("siteid")
			)
		);
	}

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

	$hta->removePage($_POST['pageid']);

    $ok=$db->CompleteTrans();
    if($ok)
    {
		$hta->save();
    	/**
    	 * Remove search index entry
    	 */
    	$db->Execute(
    		sprintf("
    			DELETE FROM
    				cms_content_search
    			WHERE
    				pageid=%u
    			AND
    				siteid=%u
    		"
    			,$_POST['pageid']
    			,$session->getValue("siteid")
    		)
    	);
		DBCheck();
		
		$sitemap = new Sitemap($config, $db);
		$sitemap->load();
		$sitemap->remove_page($_POST['pageid']);
		$sitemap->update();
		$sitemap->save();
	}
    else
    	error("There was a problem whilst approving the page removal, please try again.  If this persists please notify your designated support contact","Database Error");
?>