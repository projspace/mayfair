<?
	$order=$db->Execute(
		$sql=sprintf("
			SELECT
				shop_orders.id
				,shop_orders.name
				,shop_orders.email
				,shop_orders.tel
				,shop_orders.additional_payment
				,shop_user_orders.account_id
				,shop_orders.session_id
				,shop_orders.total
				,shop_orders.shipping
				,shop_orders.packing
				,shop_orders.country
				,shop_orders.vat_rate
			FROM
				shop_orders
			LEFT JOIN
				shop_user_orders
			ON
				shop_user_orders.order_id = shop_orders.id
			WHERE
				shop_orders.id=%u
			OR
				shop_orders.session_id=%s
		"
			,$_REQUEST['order_id']
			,$db->Quote($_REQUEST['sess_id'])
		)
	);
	$order = $order->FetchRow();
	
	if(!$order)
	{
		$order_session=$db->Execute(
			sprintf("
				SELECT
					shop_sessions.account_id
					,shop_sessions.billing_name
					,shop_sessions.billing_email
					,shop_sessions.billing_phone
					,shop_sessions.additional_payment
					,shop_sessions.session_id
					,shop_sessions.total
					,shop_sessions.shipping
					,shop_sessions.packing
					,shop_countries.name country
				FROM
					shop_sessions
				LEFT JOIN
					shop_countries
				ON
					shop_countries.id = shop_sessions.billing_country_id
				WHERE
					shop_sessions.session_id=%s
			"
				,$db->Quote($_REQUEST['sess_id'])
			)
		);
		$order_session = $order_session->FetchRow();
		$account_id = $order_session['account_id'];
		$name = $order_session['billing_name'];
		$email = $order_session['billing_email'];
		$phone = $order_session['billing_phone'];
		$additional_payment = $order_session['additional_payment'];
		$order_details = array(
			'session_id' => $order_session['session_id']
			,'total' => $order_session['total']
			,'shipping' => $order_session['shipping']
			,'packing' => $order_session['packing']
			,'country' => $order_session['country']
			,'vat_rate' => VAT
		);
		$result=$db->Execute(
			sprintf("
				SELECT
					shop_products.id
					,shop_products.code
					,shop_products.name
					,shop_products.vat_exempt
					,shop_categories.name category
					,shop_session_cart.price
					,shop_session_cart.discount
					,shop_session_cart.quantity
				FROM
				(
					shop_products
					,shop_session_cart
				)
				LEFT JOIN
					shop_categories
				ON
					shop_categories.id = shop_products.category_id
				WHERE
					shop_session_cart.product_id = shop_products.id
				AND
					shop_session_cart.session_id=%s
			"
				,$db->Quote($_REQUEST['sess_id'])
			)
		);
		$order_details['products'] = $result->GetRows();
	}
	else
	{
		$account_id = $order['account_id'];
		$name = $order['name'];
		$email = $order['email'];
		$phone = $order['tel'];
		$additional_payment = $order['additional_payment'];
		$order_details = array(
			'session_id' => $order['session_id']
			,'total' => $order['total']
			,'shipping' => $order['shipping']
			,'packing' => $order['packing']
			,'country' => $order['country']
			,'vat_rate' => $order['vat_rate']
		);
		$result=$db->Execute(
			$sql=sprintf("
				SELECT
					shop_products.id
					,shop_products.code
					,shop_products.name
					,shop_products.vat_exempt
					,shop_categories.name category
					,shop_order_products.price
					,shop_order_products.discount
					,shop_order_products.quantity
				FROM
				(
					shop_products
					,shop_order_products
				)
				LEFT JOIN
					shop_categories
				ON
					shop_categories.id = shop_products.category_id
				WHERE
					shop_order_products.product_id = shop_products.id
				AND
					shop_order_products.order_id=%u
			"
				,$order['id']
			)
		);
		$order_details['products'] = $result->GetRows();
	}
	
	$order_details['vat'] = 0;
	foreach($order_details['products'] as $row)
	{
		if($row['vat_exempt'])
		{
			$vat = 0;
		}
		else
		{
			$price_without_vat = ($row['price']-$row['discount'])*100/(100+$order_details['vat_rate']);
			$vat = $row['price']-$row['discount'] - $price_without_vat;
		}
		$order_details['vat'] += $vat;
	}

	$_REQUEST['firstname'] = $name;
	$_REQUEST['email'] = $email;
	$_REQUEST['phone'] = $phone;
?>