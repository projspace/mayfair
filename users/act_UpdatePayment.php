<?
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();
	
	$db->Execute(
		sprintf("
			UPDATE
				shop_user_accounts
			SET
				additional_payment_label=%s
				,additional_payment_session_id=%s
			WHERE
				id = %u
		"
			,$db->Quote($_REQUEST['additional_payment']?safe($_REQUEST['additional_payment_label']):'')
			,$db->Quote($_REQUEST['additional_payment']?safe($_REQUEST['additional_payment_session_id']):'')
			,$user_session->account_id
		)
	);
	
	$ok=$db->CompleteTrans();
?>