<?
	global $db;
	
	$cms_email = $db->Execute(
		sprintf("
			SELECT
				*
			FROM 
				cms_emails
			WHERE
				id = %u
		"
			,8
		)
	);
	$cms_email = $cms_email->FetchRow();
	$variables = unserialize($cms_email['variables']);
	
	$html_content = $cms_email['content'];
	$text_content = strip_tags($cms_email['content']);
	
	$subject=$cms_email['subject'];
	$email=$cms_email['to'];
	
	$embed[0]="images/email/logo.gif";
?>