<?
	$layouts=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				cms_layouts
			WHERE
				siteid=%u
			ORDER BY
				name
			ASC
		"
			,$session->getValue("siteid")
		)
	);
?>