<?
	$db->Execute(
		sprintf("
			DELETE FROM
				shop_user_addresses
			WHERE
				account_id=%u
			AND
				type = 'delivery'
			AND
				id = %u
		"
			,$user_session->account_id
			,safe($_POST['address_id'])
		)
	);
?>