<?
	//Get the current page revision
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
			,$_REQUEST['pageid']
			,$session->getValue("siteid")
		)
	);

	$edits=$db->Execute(
		sprintf("
			SELECT
				cms_pages_log.*
				,cms_content.content
				,admin_accounts.username
				,cms_layouts.name AS layout_name
			FROM
				cms_pages_log
				,cms_content
				,admin_accounts
				,cms_layouts
			WHERE
				cms_pages_log.pageid=%u
			AND
				cms_pages_log.siteid=%u
			AND
				cms_content.pageid=cms_pages_log.pageid
			AND
				cms_content.revision=cms_pages_log.content_revision
			AND
				admin_accounts.id=cms_pages_log.accountid
			AND
				cms_layouts.id=cms_pages_log.layoutid
			ORDER BY
				cms_pages_log.revision DESC
		"
			,$_REQUEST['pageid']
			,$session->getValue("siteid")
		)
	);
?>