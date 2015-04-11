<?
	$account=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				shop_user_accounts
			WHERE
				id = %u
		"
			,$user_session->account_id
		)
	);
	$account = $account->FetchRow();
	
	$addresses=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				shop_user_addresses
			WHERE
				shop_user_addresses.account_id = %u
		"
			,$user_session->account_id
		)
	);
	
	$orders=$db->Execute(
		sprintf("
			SELECT
				shop_orders.id
				,shop_orders.time
				,shop_orders.processed
				,shop_orders.dispatched
			FROM
				shop_user_orders
			LEFT JOIN
				shop_orders
			ON
				shop_user_orders.order_id = shop_orders.id
			WHERE
				shop_user_orders.account_id = %u
			ORDER BY
				shop_orders.time DESC
		"
			,$user_session->account_id
		)
	);
	
	$wishlist=$db->Execute(
		sprintf("
			SELECT
				shop_products.id
				,shop_products.guid
				,shop_products.code
				,shop_products.name
				,shop_products.price
				,shop_products.imagetype
				,shop_products.parent_id
				,shop_wishlist.id wish_id
				,shop_wishlist.quantity
				,shop_sizes.name size
				,shop_widths.name width
				,shop_colors.name color
				,shop_product_images.id image_id
				,shop_product_images.imagetype image_type
			FROM
				shop_wishlist
			JOIN
			(
				shop_product_options
				,shop_products
			)
			ON
				shop_products.id = shop_product_options.product_id
			AND
				shop_wishlist.product_id = shop_product_options.product_id
			AND
				shop_wishlist.option_id = shop_product_options.id
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
				shop_product_images
			ON
				shop_product_images.product_id = shop_products.id
			WHERE
				shop_wishlist.user_id = %u
			GROUP BY
				shop_products.id
		"
			,$user_session->account_id
		)
	);
?>