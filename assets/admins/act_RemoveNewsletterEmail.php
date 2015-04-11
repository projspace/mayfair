<?
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();
	
	$db->Execute(
		sprintf("
			DELETE FROM
				shop_newsletter_emails
			WHERE
				id=%u
		"
			,$_POST['email_id']
		)
	);
	
	$ok=$db->CompleteTrans();
	if(!$ok)
		error("There was a problem whilst removing the newsletter email, please try again.  If this persists please notify your designated support contact","Database Error");
?>