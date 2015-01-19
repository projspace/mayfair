<?
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
			,$_REQUEST['pageid']
			,$session->getValue("siteid")
		)
	);

	$result=$db->Execute(
		sprintf("
			SELECT
				id
				,parent_id
				,name
				,lft
				,rgt
			FROM
				cms_pages
			WHERE
				siteid=%u
			AND
				deleted=0
			ORDER BY
				ord
			ASC
		"
			,$session->getValue("siteid")
		)
	);
?>