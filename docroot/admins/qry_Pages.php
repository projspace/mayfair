<?
	$pages=$db->Execute(
		sprintf("
			SELECT
				cms_pages.*
				,MAX(cms_pages_log.revision) AS max_revision
				,cms_content.content
			FROM
				cms_pages_log
				,cms_pages
			LEFT JOIN
				cms_content
			ON
				cms_content.pageid=cms_pages.id
			AND
				cms_content.revision=cms_pages.content_revision
			WHERE
				cms_pages.parent_id=%u
			AND
				cms_pages.siteid=%u
			AND
				deleted=0
			AND
				cms_pages_log.pageid=cms_pages.id
			AND
				cms_pages_log.siteid=cms_pages.siteid
			GROUP BY
				cms_pages.id
			ORDER BY
				ord ASC
		"
			,$_REQUEST['parent_id']
			,$session->getValue("siteid")
		)
	);
	DBCheck();
?>