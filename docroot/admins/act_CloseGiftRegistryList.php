<?
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();
	
	$db->Execute(
		sprintf("
			UPDATE
				gift_lists
			SET
				status = 'completed'
			WHERE
				id=%u
		"
			,$_POST['list_id']
		)
	);
	
	$ok=$db->CompleteTrans();
	if(!$ok)
		error("There was a problem whilst closing the gift registry list, please try again.  If this persists please notify your designated support contact","Database Error");
?>