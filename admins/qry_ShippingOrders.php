<?
	$sql_filter = array();
	
	if($_REQUEST['records']+0)
		$sql_filter[] = sprintf("shop_orders.processed > 0");
	else
		$sql_filter[] = sprintf("shop_orders.processed = 0");

	if(is_array($_POST['order_ids']) && count($_POST['order_ids']))
		$sql_filter[] = sprintf("shop_orders.id IN (%s)", implode(',', array_map(create_function('$a', 'return $a+0;'), $_POST['order_ids'])));
	else
		$sql_filter[] = sprintf("0");
	
	if(count($sql_filter))
		$sql_filter = implode(' AND ', $sql_filter);
	else
		$sql_filter = '0';
		
	$order_count=$db->Execute(
		sprintf("
			SELECT
				COUNT(*) count
			FROM
				shop_orders
			WHERE
				%s
		"
			,$sql_filter
		)
	);
	$order_count = $order_count->FetchRow();
	$order_count = $order_count['count'];
	
	$order_value=$db->Execute(
		sprintf("
			SELECT
				SUM(paid) value
			FROM
				shop_orders
			WHERE
				%s
		"
			,$sql_filter
		)
	);
	$order_value = $order_value->FetchRow();
	$order_value = $order_value['value'];
	
	$products=$db->Execute(
		sprintf("
			SELECT
				shop_products.code
				,shop_products.name
				,shop_order_products.options
				,SUM(shop_order_products.quantity) count
				,shop_sizes.name size
				,shop_widths.name width
				,shop_colors.name color
				,shop_product_options.upc_code
			FROM
			(
				shop_orders
				,shop_order_products
			)
			LEFT JOIN
				shop_products
			ON
				shop_products.id = shop_order_products.product_id
			LEFT JOIN
				shop_product_options
			ON
				shop_order_products.option_id = shop_product_options.id
			AND
				shop_order_products.product_id = shop_product_options.product_id
			LEFT JOIN
				shop_sizes
			ON
				shop_sizes.id = shop_product_options.size_id
			LEFT JOIN
				shop_widths
			ON
				shop_widths.id = shop_product_options.width_id
			LEFT JOIN
				shop_colors
			ON
				shop_colors.id = shop_product_options.color_id
			WHERE
				shop_orders.id = shop_order_products.order_id
			AND
				%s
			GROUP BY
				shop_order_products.product_id
				,shop_order_products.options
		"
			,$sql_filter
		)
	);
	$products = $products->GetRows();
	
	$results=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				shop_orders
			WHERE
				%s
			ORDER BY
				time ASC
		"
			,$sql_filter
		)
	);
	$orders = array();
	$order_ids = array();
	while($row = $results->FetchRow())
	{
		$result=$db->Execute(
			sprintf("
				SELECT
					shop_products.code
					,shop_products.name
					,shop_order_products.options
					,SUM(shop_order_products.quantity) count
					,shop_sizes.name size
					,shop_widths.name width
					,shop_colors.name color
				FROM
				(
					shop_orders
					,shop_order_products
				)
				LEFT JOIN
					shop_products
				ON
					shop_products.id = shop_order_products.product_id
				LEFT JOIN
					shop_product_options
				ON
					shop_order_products.option_id = shop_product_options.id
				AND
					shop_order_products.product_id = shop_product_options.product_id
				LEFT JOIN
					shop_sizes
				ON
					shop_sizes.id = shop_product_options.size_id
				LEFT JOIN
					shop_widths
				ON
					shop_widths.id = shop_product_options.width_id
				LEFT JOIN
					shop_colors
				ON
					shop_colors.id = shop_product_options.color_id
				WHERE
					shop_orders.id = shop_order_products.order_id
				AND
					shop_orders.id = %u
				AND
					%s
				GROUP BY
					shop_order_products.product_id
					,shop_order_products.options
			"
				,$row['id']
				,$sql_filter
			)
		);
		$row['products'] = $result->GetRows();
		$orders[] = $row;
		$order_ids[] = $row['id'];
	}
?>