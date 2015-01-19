<?
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();
	
	$db->Execute(
		sprintf("
			UPDATE
				shop_user_accounts
			SET
				authorize_profile_id=%s
			WHERE
				id = %u
		"
			,$db->Quote($authorize_profile_id)
			,$user_id
		)
	);
	
	$ok=$db->CompleteTrans();