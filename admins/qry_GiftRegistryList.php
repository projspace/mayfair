<?
	$page = $_REQUEST['page']+0;
	if($page <= 0)
		$page = 1;
	$items_per_page = 10;
	
	$item_count=$db->Execute(
		sprintf("
			SELECT
				COUNT(DISTINCT so.id) count
			FROM
				shop_orders so
			JOIN
				shop_order_products sop
			ON
				sop.order_id = so.id
			JOIN
				gift_list_items gli
			ON
				gli.id = sop.gift_list_item_id
            WHERE
                gli.list_id = %u
		"
		    ,$_REQUEST['list_id']
        )
	);
	$item_count = $item_count->FetchRow();
	$item_count = $item_count['count'];
	
	$orders=$db->Execute(
		sprintf("
			SELECT
				so.*
			FROM
				shop_orders so
			JOIN
				shop_order_products sop
			ON
				sop.order_id = so.id
			JOIN
				gift_list_items gli
			ON
				gli.id = sop.gift_list_item_id
            WHERE
                gli.list_id = %u
			GROUP BY
				so.id
			ORDER BY
				so.time DESC
		"
		    ,$_REQUEST['list_id']
        )
	);
?>