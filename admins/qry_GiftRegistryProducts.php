<?
	$page = $_REQUEST['page']+0;
	if($page <= 0)
		$page = 1;
	$items_per_page = 10;
	
	$item_count=$db->Execute(
		sprintf("
			SELECT
				COUNT(DISTINCT sp.id) count
			FROM
				gift_list_items gli
			JOIN
				shop_products sp
			ON
				gli.product_id = sp.id
            WHERE
                gli.list_id = %u
		"
		    ,$_REQUEST['list_id']
        )
	);
	$item_count = $item_count->FetchRow();
	$item_count = $item_count['count'];
	
	$products=$db->Execute(
		sprintf("
			SELECT
				sp.*
				,spo.upc_code
				,gli.quantity
				,sp.price + spo.price price
				,SUM(sop.quantity) bought
			FROM
				gift_list_items gli
			JOIN
				shop_products sp
			ON
				gli.product_id = sp.id
            LEFT JOIN
                shop_product_options spo
            ON
                spo.product_id = sp.id
            AND
                spo.id = gli.option_id
            LEFT JOIN
                shop_order_products sop
            ON
                gli.id = sop.gift_list_item_id
            AND
                gli.product_id = sop.product_id
            WHERE
                gli.list_id = %u
			GROUP BY
				sp.id
            LIMIT
                %u, %u
		"
		    ,$_REQUEST['list_id']
            ,($page - 1)*$items_per_page
			,$items_per_page
        )
	);
?>