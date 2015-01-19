<?
	$order=$db->Execute(
		$sql = sprintf("
			SELECT
				shop_orders.*
			FROM
				shop_user_orders
				,shop_orders
			WHERE
				shop_user_orders.account_id = %u
			AND
				shop_user_orders.order_id = shop_orders.id
			AND
				shop_orders.id = %u
		"
			,$user_session->account_id
			,safe($_REQUEST['order_id'])
		)
	);
	$order = $order->FetchRow();
	
	$products=$db->Execute(
		sprintf("
			SELECT
				shop_order_products.options AS cart_options
				,shop_order_products.price AS cart_price
				,shop_order_products.quantity AS cart_quantity
				,shop_products.id
				,shop_products.name
				,shop_products.options
				,shop_products.imagetype
				,GROUP_CONCAT(DISTINCT shop_meta_tags.name SEPARATOR ', ') tags
			FROM
			(
				shop_order_products
			)
			LEFT JOIN
			(
				shop_products
			)
			ON
				shop_products.id=shop_order_products.product_id
			LEFT JOIN
			(	
				shop_product_tags
				,shop_meta_tags
			)
			ON
				shop_product_tags.product_id = shop_products.id
			AND
				shop_product_tags.tag_id = shop_meta_tags.id
			WHERE
				shop_order_products.order_id=%u
			GROUP BY
				shop_order_products.id
			ORDER BY
				shop_order_products.id ASC
		"
			,$order['id']
		)
	);
	$products = $products->GetRows();
?>