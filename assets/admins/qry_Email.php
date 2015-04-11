<?
	$email=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				cms_emails
			WHERE
				id = %u
		"
			,$_REQUEST['email_id']
		)
	);
	$email = $email->FetchRow();
?>