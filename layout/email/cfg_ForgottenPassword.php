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
			,6
		)
	);
	$cms_email = $cms_email->FetchRow();
	$variables = unserialize($cms_email['variables']);
	
	$tmp = array();
	$tmp['[password]'] = $vars['password'];
	
	$replace = array();
	foreach($variables as $variable=>$unused)
		if(isset($tmp[$variable]))
			$replace[$variable] = $tmp[$variable];
		else
			$replace[$variable] = '';
			
	$content = str_replace(array_keys($replace), $replace, $cms_email['content']);
	
	$subject=$cms_email['subject'];
	$embed[0]="images/email/logo.gif";
?>