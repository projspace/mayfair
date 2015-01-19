<?
	$page=$db->Execute(
		sprintf("
			SELECT
				cms_pages.*
				,cms_layouts.name AS layout_name
				,admin_accounts.username
				,cms_pages_log.time
			FROM
				cms_pages
				,cms_layouts
				,cms_pages_log
				,admin_accounts
			WHERE
				cms_pages.id=%u
			AND
				cms_pages.siteid=%u
			AND
				cms_layouts.id=cms_pages.layoutid
			AND
				cms_pages_log.pageid=cms_pages.id
			AND
				cms_pages_log.siteid=cms_pages.siteid
			AND
				cms_pages_log.revision=cms_pages.revision
			AND
				admin_accounts.id=cms_pages_log.accountid
		"
			,$_REQUEST['pageid']
			,$session->getValue("siteid")
		)
	);

	$layoutid=$page->fields['layoutid'];

	$content=$db->Execute(
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
			,$page->fields['content_revision']
		)
	);
?>