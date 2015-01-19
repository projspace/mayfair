<?
	$page = safe($_REQUEST['page'])+0;
	if($page <= 0)
		$page = 1;
	$items_per_page = 10;
	
	$item_count=$db->Execute(
		sprintf("
			SELECT
				COUNT(DISTINCT shop_orders.id) count
			FROM
				shop_user_orders
			LEFT JOIN
				shop_orders
			ON
				shop_user_orders.order_id = shop_orders.id
			WHERE
				shop_user_orders.account_id = %u
		"
			,$user_session->account_id
		)
	);
	$item_count = $item_count->FetchRow();
	$item_count = $item_count['count'];
	
	$orders=$db->Execute(
		sprintf("
			SELECT
				shop_orders.id
				,shop_orders.time
				,shop_orders.paid
				,shop_orders.processed
			FROM
				shop_user_orders
			LEFT JOIN
				shop_orders
			ON
				shop_user_orders.order_id = shop_orders.id
			WHERE
				shop_user_orders.account_id = %u
			ORDER BY
				shop_orders.time DESC
			LIMIT
				%u, %u
		"
			,$user_session->account_id
			,($page - 1)*$items_per_page
			,$items_per_page
		)
	);
?>