<?
	$db->Execute(
		sprintf("
			UPDATE
				cms_pages
			SET
				pendingremove=1
			WHERE
				id=%u
			AND
				siteid=%u
		"
			,$_POST['pageid']
			,$session->getValue("siteid")
		)
	);
	DBCheck();
?>