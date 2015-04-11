<?
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();
	
	$db->Execute(
		sprintf("
			DELETE FROM
				admin_sessions
			WHERE
				id=%u
		"
			,$_POST['session_id']
		)
	);
	
	$ok=$db->CompleteTrans();
	if(!$ok)
    	error("There was a problem whilst removing the session, please try again.  If this persists please notify your designated support contact","Database Error");
?>