<?
	$page = $_REQUEST['page']+0;
	if($page <= 0)
		$page = 1;
	$items_per_page = GIFT_PAGINATION;
	
	$display = ($_REQUEST['display'] == 'bought')?'bought':'all';
	$sort_field = ($_REQUEST['sort'] == 'price')?'price':'name';
	if(strtolower(trim($_REQUEST['sort_dir'])) == 'desc')
		$sort_dir = 'desc';
	else
		$sort_dir = 'asc';
	
	$gift_list=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				gift_lists
			WHERE
				gift_lists.code = %s
		"
			,$db->Quote($_REQUEST['code'])
		)
	);
	$gift_list = $gift_list->FetchRow();
	
	$sql_where = array();
	$sql_where[] = sprintf("gli.list_id = %u", $gift_list['id']);
	if($display == 'bought')
		$sql_where[] = sprintf("(SELECT SUM(sop.quantity) FROM shop_order_products sop WHERE sop.gift_list_item_id = gli.id ) > 0");
	
	$item_count=$db->Execute(
		sprintf("
			SELECT
				COUNT(*) count
			FROM
				gift_list_items gli
			WHERE
				%s
		"
			,implode(' AND ', $sql_where)
		)
	);
	$item_count = $item_count->FetchRow();
	$item_count = $item_count['count'];
	
	$products=$db->Execute(
		$sql=sprintf("
			SELECT
				gli.id
				,shop_products.id product_id
				,shop_products.guid
				,shop_products.code
				,shop_products.name
				,shop_products.price + spo.price price
				,gli.quantity
				,gli.option_id
				,shop_sizes.name size
				,shop_widths.name width
				,shop_colors.name color
				,IFNULL(spi.id, shop_product_images.id) image_id
				,IFNULL(spi.imagetype, shop_product_images.imagetype) image_type
				,(SELECT SUM(sop.quantity) FROM shop_order_products sop WHERE sop.gift_list_item_id = gli.id ) bought
				,spo.quantity stock
			FROM
				gift_list_items gli
			JOIN
				shop_products
			ON
				shop_products.id = gli.product_id
			JOIN
				shop_product_options spo
			ON
				spo.id = gli.option_id
			AND
				spo.product_id = gli.product_id
			LEFT JOIN
				shop_sizes
			ON
				shop_sizes.id = spo.size_id
			LEFT JOIN
				shop_widths
			ON
				shop_widths.id = spo.width_id
			LEFT JOIN
				shop_colors
			ON
				shop_colors.id = spo.color_id
			LEFT JOIN
				shop_product_images
			ON
				shop_product_images.product_id = shop_products.id
			LEFT JOIN
				shop_product_images spi
			ON
				spi.product_id = shop_products.id
			AND
				spi.color_id = spo.color_id
			WHERE
				%s
			GROUP BY
				gli.id
			ORDER BY
				shop_products.%s %s
			LIMIT
				%u, %u
		"
			,implode(' AND ', $sql_where)
			,$sort_field
			,$sort_dir
			,($page - 1)*$items_per_page
			,$items_per_page
		)
	);
    //die($sql);
?>