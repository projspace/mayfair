<?
	$current_site=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				cms_sites
			WHERE
				id=%u
		"
			,$session->getValue("siteid")
		)
	);
?>