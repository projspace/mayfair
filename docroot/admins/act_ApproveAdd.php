<?
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();

	if(!isset($pageid))
		$pageid=$_POST['pageid'];

	/**
	 * Get Maximum order variable for section
	 */
	$db->Execute(
		sprintf("
			UPDATE
				cms_pages
			SET
				pendingadd=0
			WHERE
				pendingadd=1
			AND
				id=%u
			AND
				siteid=%u
		"
			,$pageid
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
			,$pageid
			,$session->account_id
			,1
			,1
			,$db->Quote("approved addition")
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
			,$pageid
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

	$hta->addPage($trailarr,$pageid);
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
			,$pageid
			,$session->getValue("siteid")
		)
	);

	//Get content for search index entry
	$content=$db->Execute(
		sprintf("
			SELECT
				cms_pages.name
				,cms_content.*
			FROM
				cms_pages
				,cms_content
			WHERE
				cms_pages.id=%u
			AND
				cms_pages.siteid=%u
			AND
				cms_content.pageid=cms_pages.id
			AND
				cms_pages.revision=1
			AND
				cms_content.revision=cms_pages.content_revision
		"
			,$pageid
			,$session->getValue("siteid")
		)
	);

    $ok=$db->CompleteTrans();
    if($ok)
    {
		$hta->save();
    	/**
    	 * Create search index entry
    	 */
    	$db->Execute(
    		sprintf("
    			INSERT INTO
    				cms_content_search (
    					pageid
    					,siteid
    					,name
    					,content
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
    				)
    		"
    			,$pageid
    			,$session->getValue("siteid")
    			,$db->Quote($content->fields['name'])
    			,$db->Quote($content->fields['content'])
				,$db->Quote($content->fields['meta_title'])
				,$db->Quote($content->fields['meta_keywords'])
				,$db->Quote($content->fields['meta_description'])
			)
		);
		DBCheck();
		
		$sitemap = new Sitemap($config, $db);
		$sitemap->load();
		$sitemap->add_page($pageid);
		$sitemap->update();
		$sitemap->save();
	}
    else
    	error("There was a problem whilst approving the page addition, please try again.  If this persists please notify your designated support contact","Database Error");
?>