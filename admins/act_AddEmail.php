<?
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();

	$variables = array();
	if(is_array($_POST['variables']))
	foreach($_POST['variables'] as $key=>$variable)
	{
		$variable = trim($variable);
		if($variable == '')
			continue;
		$variables[$variable] = $_POST['descriptions'][$key];
	}
	
	$db->Execute(
		$sql = sprintf("
			INSERT INTO
				cms_emails
			SET
				`name` = %s
				,`subject` = %s
				,`content` = %s
				,`to` = %s
				,`cc` = %s
				,`bcc` = %s
				,`variables` = %s
		"
			,$db->Quote(trim($_POST['name']))
			,$db->Quote(trim($_POST['subject']))
			,$db->Quote($_POST['content'][0])
			,$db->Quote($_POST['to'])
			,$db->Quote($_POST['cc'])
			,$db->Quote($_POST['bcc'])
			,$db->Quote(serialize($variables))
		)
	);
	$email_id=$db->Insert_ID();
	DBCheck(1);

	$ok=$db->CompleteTrans();
	if(!$ok)
		error("There was a problem whilst adding the email, please try again.  If this problem persists please contact your designated support contact","Database Error");
?>