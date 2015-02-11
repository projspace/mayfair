<?
	$sql_filter = array();
	
	$sql_filter[] = sprintf("shop_orders.processed = 0");
			
	if(count($sql_filter))
		$sql_filter = implode(' AND ', $sql_filter);
	else
		$sql_filter = '1';

	$orders=$db->Execute(
		sprintf("
			SELECT
				shop_orders.*
				,shop_order_products.price order_price
				,shop_order_products.quantity order_quantity
				,shop_order_products.promotional_discount*100/(shop_order_products.price*shop_order_products.quantity) discount_percentage
				,GROUP_CONCAT(shop_order_txnvars.name SEPARATOR '[::]') txn_keys
				,GROUP_CONCAT(shop_order_txnvars.value SEPARATOR '[::]') txn_values
				,shop_product_options.upc_code
				,shop_sizes.name size
				,shop_widths.code width
				,shop_colors.code color
				,IFNULL(billing_sc1.code, billing_sc2.code) country_code
				,delivery_sc.code delivery_country_code
				,ups_services.fc_code shipping_method
			FROM
				shop_orders
			JOIN
				shop_order_products
			ON
				shop_order_products.order_id = shop_orders.id
			LEFT JOIN
				shop_order_txnvars
			ON
				shop_order_txnvars.order_id = shop_orders.id
			LEFT JOIN
				shop_product_options
			ON
				shop_product_options.product_id = shop_order_products.product_id
			AND
				shop_product_options.id = shop_order_products.option_id
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
			LEFT JOIN
				shop_countries billing_sc1
			ON
				billing_sc1.id = shop_orders.country_id
			LEFT JOIN
				shop_countries billing_sc2
			ON
				billing_sc2.name = shop_orders.country
			LEFT JOIN
				shop_countries delivery_sc
			ON
				delivery_sc.id = shop_orders.delivery_country_id
			LEFT JOIN
				ups_services
			ON
				ups_services.code = shop_orders.delivery_service_code
			WHERE
				%s
			GROUP BY
				shop_orders.id
				,shop_order_products.id
			ORDER BY
				shop_orders.time ASC
		"
			,$sql_filter
		)
	);
?>