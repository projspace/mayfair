<?
	$account = $db->Execute(
		$sql = sprintf("
			SELECT
				id
				,email
				,password
			FROM
				shop_user_accounts
			WHERE
				email=%s
			LIMIT 1
		"
			,$db->Quote(safe($_REQUEST['email']))
		)
	);
	$account = $account->FetchRow();
	if($account)
		$sent=$mail->sendMessage(array('password'=>$account['password']),"ForgottenPassword",$account['email'],'');
?>