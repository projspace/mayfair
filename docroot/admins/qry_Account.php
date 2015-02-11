<?
	$account=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				admin_accounts
			WHERE
				id=%u
		"
			,$_REQUEST['account_id']
		)
	);
	$account = $account->FetchRow();
?>