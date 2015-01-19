<?
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();

	if(!isset($pageid))
		$pageid=$_POST['pageid'];

	include("qry_ApproveEdit.php");

	//Set revision to selected
	$db->Execute(
		sprintf("
			UPDATE
				cms_pages
			SET
				revision=%u
				,content_revision=%u
				,parent_id=%u
				,layoutid=%u
				,valid_from=%s
				,valid_to=%s
				,pagetype=%u
				,name=%s
				,page=%s
				,url=%s
				,pendingedit=0
			WHERE
				id=%u
			AND
				siteid=%u
		"
			,$new_page->fields['revision']
			,$new_page->fields['content_revision']
			,$new_page->fields['parent_id']
			,$new_page->fields['layoutid']
			,$db->DBDate($new_page->fields['valid_from'])
			,$db->DBDate($new_page->fields['valid_to'])
			,$new_page->fields['pagetype']
			,$db->Quote($new_page->fields['name'])
			,$db->Quote(name2page($new_page->fields['name']))
			,$db->Quote(makeurl($new_page->fields['parent_id'],$new_page->fields['name']))
			,$_POST['pageid']
			,$session->getValue("siteid")
		)
	);

	$db->Execute(
		sprintf("
			UPDATE
				cms_pages_log
			SET
				accepted=1
			WHERE
				pageid=%u
			AND
				siteid=%u
			AND
				revision=%u
		"
			,$_POST['pageid']
			,$session->getValue("siteid")
			,$new_page->fields['revision']
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
			,$new_page->fields['revision']
			,$new_page->fields['content_revision']
			,$db->Quote("approved edit")
			,$db->DBTimestamp(time())
		)
	);

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
    {
		$hta->save();
    	/**
    	 * Update search index entry
    	 */
    	$db->Execute(
    		sprintf("
    			UPDATE
    				cms_content_search
    			SET
    				name=%s
    				,content=%s
    				,meta_title=%s
    				,meta_keywords=%s
    				,meta_description=%s
    			WHERE
    				pageid=%u
    			AND
    				siteid=%u
    		"
    			,$db->Quote($new_page->fields['name'])
    			,$db->Quote($new_content->fields['content'])
				,$db->Quote($new_content->fields['meta_title'])
				,$db->Quote($new_content->fields['meta_keywords'])
				,$db->Quote($new_content->fields['meta_description'])
    			,$_POST['pageid']
    			,$session->getValue("siteid")
			)
		);
		DBCheck();
		
		$sitemap = new Sitemap($config, $db);
		$sitemap->load();
		$sitemap->update_page($_POST['pageid']);
		$sitemap->update();
		$sitemap->save();
	}
    else
    	error("There was a problem whilst approving the page edit, please try again.  If this persists please notify your designated support contact","Database Error");
?>