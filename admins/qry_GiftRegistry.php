<?
	$page = $_REQUEST['page']+0;
	if($page <= 0)
		$page = 1;
	$items_per_page = 10;

    $sql_filter = array();

    // custom filters
	if($_REQUEST['status'])
		$sql_filter[] = sprintf('gl.status = %s', $db->Quote(safe($_REQUEST['status'])));

    if(count($sql_filter))
		$sql_filter = implode(' AND ', $sql_filter);
	else
		$sql_filter = '1';

	$item_count=$db->Execute(
		sprintf("
			SELECT
				COUNT(DISTINCT gl.id) count
			FROM
				gift_lists gl
			LEFT JOIN
				gift_list_items gli
			ON
				gli.list_id = gl.id
			/*JOIN
				shop_order_products sop
			ON
				sop.gift_list_item_id = gli.id*/
            WHERE
				%s
		"
		    ,$sql_filter
        )
	);
	$item_count = $item_count->FetchRow();
	$item_count = $item_count['count'];
	
	$lists=$db->Execute(
		$sql=sprintf("
			SELECT
				gl.*
				,(SELECT SUM(quantity) FROM gift_list_items gli2 WHERE gli2.list_id = gl.id) quantity
				,(SELECT SUM(sop2.quantity) FROM shop_order_products sop2, gift_list_items gli2 WHERE gli2.list_id = gl.id AND sop2.gift_list_item_id = gli2.id) bought
			FROM
				gift_lists gl
			LEFT JOIN
				gift_list_items gli
			ON
				gli.list_id = gl.id
			/*JOIN
				shop_order_products sop
			ON
				sop.gift_list_item_id = gli.id*/
            WHERE
				%s
			GROUP BY
				gl.id
			ORDER BY
				gl.name ASC
			LIMIT
				%u, %u
		"
			,$sql_filter
            ,($page - 1)*$items_per_page
			,$items_per_page
		)
	);
?>