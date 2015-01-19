<?
	if($account_id!=$session->account_id)
	{
		$db->SetTransactionMode("SERIALIZABLE");
		$db->StartTrans();
		
		$db->Execute(
			sprintf("
				DELETE FROM
					admin_accounts
				WHERE
					username!='admin'
				AND
					id=%u
			"
				,$_POST['account_id']
			)
		);
		
		$ok=$db->CompleteTrans();
		if(!$ok)
			error("There was a problem whilst removing the account, please try again.  If this persists please notify your designated support contact","Database Error");
	}
	else
		error("You cannot delete your own account", "Stop");
?>