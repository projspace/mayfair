<?
	//Get the latest revision
	$new_page=$db->Execute(
		sprintf("
			SELECT
				cms_pages_log.*
				,cms_layouts.name AS layout_name
				,admin_accounts.username
			FROM
				cms_pages_log
				,cms_layouts
				,admin_accounts
			WHERE
				cms_pages_log.pageid=%u
			AND
				cms_pages_log.siteid=%u
			AND
				cms_pages_log.revision=%u
			AND
				cms_layouts.id=cms_pages_log.layoutid
			AND
				admin_accounts.id=cms_pages_log.accountid
		"
			,$_REQUEST['pageid']
			,$session->getValue("siteid")
			,$revision
		)
	);

	$layoutid=$page->fields['layoutid'];

	$new_content=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				cms_content
			WHERE
				pageid=%u
			AND
				revision=%u
		"
			,$_REQUEST['pageid']
			,$new_page->fields['content_revision']
		)
	);
?>