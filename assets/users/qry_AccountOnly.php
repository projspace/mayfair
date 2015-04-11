<?
	$account=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				shop_user_accounts
			WHERE
				id = %u
		"
			,$user_session->account_id
		)
	);
	$account = $account->FetchRow();
