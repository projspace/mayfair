<?
	$check = $db->Execute(
		$sql = sprintf("
			SELECT
				id
			FROM
				shop_user_accounts
			WHERE
				id != %u
			AND
				email = %s
		"
			,$user_session->account_id
			,$db->Quote(safe($_POST['email']))
		)
	);
	if($check->FetchRow())
	{
		$ok = false;
		$_SESSION['change_email_error'] = 'This email has already been taken. Please choose another.';
		return;
	}
	
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();
	
	$db->Execute(
		sprintf("
			UPDATE
				shop_user_accounts
			SET
				email=%s
			WHERE
				id = %u
		"
			,$db->Quote(safe($_POST['email']))
			,$user_session->account_id
		)
	);
	
	$ok=$db->CompleteTrans();
	if(!$ok)
		$_SESSION['change_email_error'] = 'There was a problem whilst updating the email, please try again.';
?>