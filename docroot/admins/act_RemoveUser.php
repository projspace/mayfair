<?
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();
	
	$db->Execute(
		sprintf("
			DELETE FROM
				shop_user_accounts
			WHERE
				id=%u
		"
			,$_POST['user_id']
		)
	);
	
	$ok=$db->CompleteTrans();
	if(!$ok)
		error("There was a problem whilst removing the user, please try again.  If this persists please notify your designated support contact","Database Error");
?>