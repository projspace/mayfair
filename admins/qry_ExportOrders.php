<?
	$last_export=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				shop_variables
			WHERE
				name = 'last_order_export'
		"
		)
	);
	$last_export = $last_export->FetchRow();
	
	$orders=$db->Execute(
		sprintf("
			SELECT
				shop_orders.*
				,shop_order_products.price
				,shop_order_products.quantity
				,shop_products.code
				,shop_products.name
				,shop_products.vat
			FROM
			(
				shop_order_products
				,shop_orders
			)
			LEFT JOIN
				shop_products
			ON
				shop_products.id = shop_order_products.product_id
			WHERE
				shop_orders.id = shop_order_products.order_id
			AND
				shop_orders.`time` > %u
			ORDER BY
				shop_orders.`time` ASC
		"
			,$last_export['value']
		)
	);
?>