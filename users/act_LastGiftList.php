<?
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();
	
	$db->Execute(
		sprintf("
			UPDATE
				shop_sessions
			SET
				last_gift_list_id=%u
			WHERE
				session_id=%s
		"
			,$gift_list['id']
			,$db->Quote($session->session_id)
		)
	);
	
	$ok=$db->CompleteTrans();
?>