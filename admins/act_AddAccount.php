<?
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();
	
	$db->Execute(
		sprintf("
			INSERT INTO
				admin_accounts
			SET
				username = %s
				,password = %s
				,email = %s
				,group_id = %u
		"
			,$db->Quote($_POST['username'])
			,$db->Quote($_POST['password'])
			,$db->Quote($_POST['email'])
			,$_POST['group_id']
		)
	);
	
	$ok=$db->CompleteTrans();
	if(!$ok)
    	error("There was a problem whilst adding the account, please try again.  If this persists please notify your designated support contact","Database Error");
?>