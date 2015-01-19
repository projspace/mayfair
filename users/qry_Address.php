<?
	$address=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				shop_user_addresses
			WHERE
				account_id = %u
			AND
				id = %u
		"
			,$user_session->account_id
			,$_REQUEST['address_id']
		)
	);
	$address = $address->FetchRow();
?>