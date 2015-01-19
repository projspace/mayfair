<?
	$order=$db->Execute(
		sprintf("
			SELECT
				shop_orders.*
				,admin_accounts.username refund_admin
				,shipping_options.name delivery_service_code_name
			FROM
				shop_orders
			LEFT JOIN
				admin_accounts
			ON
				admin_accounts.id = shop_orders.refund_admin_id
			LEFT JOIN
				shipping_options
			ON
				shipping_options.id = shop_orders.delivery_service_code
			WHERE
				shop_orders.id=%u
		"
			,$_REQUEST['order_id']
		)
	);
	$order = $order->FetchRow();

	$txnvars=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				shop_order_txnvars
			WHERE
				order_id=%u
			ORDER BY
				id
			ASC
		"
			,$_REQUEST['order_id']
		)
	);

	$txnvars=get_txnvars($txnvars->GetRows());

	$products=$db->Execute(
		sprintf("
			SELECT
				shop_products.*
				,shop_order_products.options AS order_options
				,shop_order_products.custom AS order_custom
				,shop_order_products.price AS order_price
				,shop_order_products.discount AS order_discount
				,shop_order_products.quantity AS order_quantity
				,shop_order_products.refunded
				,shop_order_products.promotional_discount
				,shop_brands.name AS brand_name
				,shop_sizes.name size
				,shop_widths.name width
				,shop_colors.name color
			FROM
			(
				shop_products
				,shop_product_options
				,shop_order_products
				,shop_brands
			)
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
				shop_brands.id=shop_products.brand_id
			AND
				shop_products.id=shop_order_products.product_id
			AND
				shop_order_products.option_id = shop_product_options.id
			AND
				shop_order_products.product_id = shop_product_options.product_id
			AND
				shop_order_products.order_id=%u
		"
			,$_REQUEST['order_id']
		)
	);
?>