<?
	$gift_list=$db->Execute(
		sprintf("
			SELECT
				gl.*
				,addr.name delivery_name
				,addr.email delivery_email
				,addr.phone delivery_phone
				,addr.line1 delivery_line1
				,addr.line2 delivery_line2
				,addr.line3 delivery_line3
				,addr.line4 delivery_line4
				,addr.postcode delivery_postcode
				,addr.country_id delivery_country_id
				,sc.name delivery_country
				,SUM(gli.quantity) count
			FROM
				gift_lists gl
            LEFT JOIN
                shop_user_addresses addr
            ON
                addr.account_id = gl.account_id
            AND
                addr.id = gl.delivery_address_id
            LEFT JOIN
                shop_countries sc
            ON
                sc.id = addr.country_id
            LEFT JOIN
                gift_list_items gli
            ON
                gli.list_id = gl.id
			WHERE
				gl.id=%u
            GROUP BY
                gl.id
		"
			,$_REQUEST['list_id']
		)
	);
	$gift_list = $gift_list->FetchRow();

	$products=$db->Execute(
		sprintf("
			SELECT
				sp.*
				,so.gift_message
				,so.name purchaser
				,sop.quantity
				,shop_sizes.name size
				,shop_widths.name width
				,shop_colors.name color
				,spo.upc_code
			FROM
				gift_list_items gli
			JOIN
				shop_products sp
			ON
				gli.product_id = sp.id
            JOIN
            (
                shop_order_products sop
                ,shop_orders so
            )
            ON
                gli.id = sop.gift_list_item_id
            AND
                gli.product_id = sop.product_id
            AND
                so.id = sop.order_id
            LEFT JOIN
                shop_product_options spo
            ON
                sop.option_id = spo.id
			AND
				sop.product_id = spo.product_id
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
            WHERE
                gli.list_id = %u
			GROUP BY
				sp.id
				,sop.id
            ORDER BY
                so.`time` ASC
		"
		    ,$_REQUEST['list_id']
        )
	);
    $products = $products->GetRows();
?>