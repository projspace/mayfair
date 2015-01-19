<?
	$page = $_REQUEST['page']+0;
	if($page <= 0)
		$page = 1;
	$items_per_page = 10;
	
	$sql_where = array();
	if(count($sql_where))
		$sql_where = implode(' AND ', $sql_where);
	else
		$sql_where = '1';
	
	$item_count=$db->Execute(
		sprintf("
			SELECT
				COUNT(DISTINCT shop_pick_and_pack.id) count
			FROM
				shop_pick_and_pack
				,shop_pick_and_pack_orders
			WHERE
				shop_pick_and_pack.id = shop_pick_and_pack_orders.pap_id
			AND
				%s
			GROUP BY
				shop_pick_and_pack.id
		"
			,$sql_where
		)
	);
	$item_count = $item_count->FetchRow();
	$item_count = $item_count['count'];
	
	$history=$db->Execute(
		sprintf("
			SELECT
				shop_pick_and_pack.*
				,GROUP_CONCAT(shop_pick_and_pack_orders.order_id) orders
			FROM
				shop_pick_and_pack
				,shop_pick_and_pack_orders
			WHERE
				shop_pick_and_pack.id = shop_pick_and_pack_orders.pap_id
			AND
				%s
			GROUP BY
				shop_pick_and_pack.id
			ORDER BY
				shop_pick_and_pack.time DESC
			LIMIT
				%u, %u
		"
			,$sql_where
			,($page - 1)*$items_per_page
			,$items_per_page
		)
	);
?>