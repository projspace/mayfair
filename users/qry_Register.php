<?
	$order=$db->Execute(
		sprintf("
			SELECT
				shop_orders.*
			FROM
				shop_orders
			LEFT JOIN
				shop_user_orders
			ON
				shop_user_orders.order_id = shop_orders.id
			WHERE
				shop_orders.session_id=%s
			AND
				shop_user_orders.account_id IS NULL
		"
			,$db->Quote($_REQUEST['session_id'])
		)
	);
	$order = $order->FetchRow();
	if(!$order)
	{
		$order_session=$db->Execute(
			$sql = sprintf("
				SELECT
					shop_sessions.*
				FROM
					shop_sessions
				WHERE
					shop_sessions.session_id=%s
				AND
					shop_sessions.account_id = 0
			"
				,$db->Quote($_REQUEST['session_id'])
			)
		);
		$order_session = $order_session->FetchRow();
	}
	
	$results=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				shop_countries
			ORDER BY
				name
			ASC
		"
		)
	);
	$countries = array();
	while($row = $results->FetchRow())
		$countries[strtolower(trim($row['name']))] = $row['id'];
?>
