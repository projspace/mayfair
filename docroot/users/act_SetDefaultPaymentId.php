<?php 

	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();
	
	
	$account=$db->Execute(
		$q = sprintf("
			UPDATE
				shop_user_accounts
			SET
				`authorize_default_payment_id` = %u	
			WHERE
				`authorize_profile_id` = %u
		"	,$payment_profile_id
			,$authorize_profile_id
		)
	);
	
	$ok=$db->CompleteTrans();	