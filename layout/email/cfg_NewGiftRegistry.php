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
			,11
		)
	);
	$cms_email = $cms_email->FetchRow();
	$variables = unserialize($cms_email['variables']);
	
	$tmp = array();
	$tmp['[link]'] = $vars['link'];

	$replace = array();
	foreach($variables as $variable=>$unused)
		if(isset($tmp[$variable]))
			$replace[$variable] = $tmp[$variable];
		else
			$replace[$variable] = '';
			
	$html_content = str_replace(array_keys($replace), $replace, $cms_email['content']);
	$text_content = strip_tags(str_replace(array_keys($replace), $replace, $cms_email['content']));
	
	$subject=$cms_email['subject'];
    $email=$cms_email['to'];
	$embed[0]="images/email/logo.gif";
?>