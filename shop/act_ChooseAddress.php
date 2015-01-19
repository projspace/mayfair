<?
	$delivery=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				shop_user_addresses
			WHERE
				account_id=%u
			AND
				id = %u
		"
			,$user_session->account_id
			,safe($_REQUEST['address_id'])
		)
	);
	$delivery = $delivery->FetchRow();
	if($delivery)
		$db->Execute(
			sprintf("
				UPDATE
					shop_sessions
				SET
					delivery_name=%s
					,delivery_email=%s
					,delivery_phone=%s
					,delivery_line1=%s
					,delivery_line2=%s
					,delivery_line3=%s
					,delivery_line4=%s
					,delivery_postcode=%s
					,delivery_country_id=%u
				WHERE
					session_id=%s
			"
				,$db->Quote($delivery['name'])
				,$db->Quote($delivery['email'])
				,$db->Quote($delivery['phone'])
				,$db->Quote($delivery['line1'])
				,$db->Quote($delivery['line2'])
				,$db->Quote($delivery['line3'])
				,$db->Quote($delivery['line4'])
				,$db->Quote($delivery['postcode'])
				,$delivery['country_id']
				,$db->Quote($session->session_id)
			)
		);
?>