<?
	$emails=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				cms_emails
            WHERE
                hidden = 0
		"
		)
	);
?>