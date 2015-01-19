<?
	if(trim($_REQUEST['additional_payment_session_id']) == '')
	{
		$order_address = false;
		return;
	}
	
	$order_address=$db->Execute(
		sprintf("
			SELECT
				shop_orders.name billing_name
				,shop_orders.email billing_email
				,shop_orders.tel billing_phone
				,shop_orders.address billing_line1
				,'' billing_line2
				,'' billing_line3
				,'' billing_line4
				,shop_orders.postcode billing_postcode
				,sc_billing.id billing_country_id
				
				,shop_orders.delivery_name
				,shop_orders.delivery_email
				,shop_orders.delivery_phone
				,shop_orders.delivery_address delivery_line1
				,'' delivery_line2
				,'' delivery_line3
				,'' delivery_line4
				,shop_orders.delivery_postcode
				,sc_delivery.id delivery_country_id
			FROM
				shop_orders
			LEFT JOIN
				shop_user_orders
			ON
				shop_user_orders.order_id = shop_orders.id
			LEFT JOIN
				shop_countries sc_billing
			ON
				sc_billing.name = shop_orders.country
			LEFT JOIN
				shop_countries sc_delivery
			ON
				sc_delivery.name = shop_orders.delivery_country
			WHERE
				shop_orders.session_id=%s
			AND
				shop_user_orders.account_id IS NULL
		"
			,$db->Quote($_REQUEST['additional_payment_session_id'])
		)
	);
	$order_address = $order_address->FetchRow();
	if(!$order_address)
	{
		$order_address=$db->Execute(
			$sql = sprintf("
				SELECT
					shop_sessions.billing_name
					,shop_sessions.billing_email
					,shop_sessions.billing_phone
					,shop_sessions.billing_line1
					,shop_sessions.billing_line2
					,shop_sessions.billing_line3
					,shop_sessions.billing_line4
					,shop_sessions.billing_postcode
					,shop_sessions.billing_country_id
					,shop_sessions.delivery_name
					,shop_sessions.delivery_email
					,shop_sessions.delivery_phone
					,shop_sessions.delivery_line1
					,shop_sessions.delivery_line2
					,shop_sessions.delivery_line3
					,shop_sessions.delivery_line4
					,shop_sessions.delivery_postcode
					,shop_sessions.delivery_country_id
				FROM
					shop_sessions
				WHERE
					shop_sessions.session_id=%s
				AND
					shop_sessions.account_id = 0
			"
				,$db->Quote($_REQUEST['additional_payment_session_id'])
			)
		);
		$order_address = $order_address->FetchRow();
	}
?>
