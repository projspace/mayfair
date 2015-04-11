<?
	$warnings = array();
	
	$cart=$db->Execute(
		$sql = sprintf("
			SELECT DISTINCT
				shop_session_cart.id AS cart_id
				,shop_session_cart.price AS cart_price
				,shop_session_cart.discount AS cart_discount
				,shop_session_cart.quantity AS cart_quantity
				,shop_products.id
				,shop_products.name
				,shop_products.guid
				,shop_products.price
				,shop_products.discount
				,shop_products.weight
				,shop_products.description
				,shop_products.short_description
				,shop_products.soldout
				,shop_products.options
				,shop_products.shipping
				,shop_products.no_shipping
				,shop_products.pick_up_only
				,shop_products.packing
				,shop_products.custom
				,shop_products.gift
				,shop_products.imagetype
				,shop_products.vat
				,shop_session_cart.parent_id
				,shop_sizes.name size
				,shop_widths.name width
				,shop_colors.name color
				,shop_product_images.id image_id
				,shop_product_images.imagetype image_type
				,(shop_products.exclude_discounts OR shop_categories.exclude_discounts) exclude_discounts
			FROM
			(
				shop_products
				,shop_session_cart
				,shop_categories
				,shop_product_options
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
			LEFT JOIN
				shop_product_images
			ON
				shop_product_images.product_id = shop_products.id
			WHERE
				shop_products.id=shop_session_cart.product_id
			AND
				shop_categories.id=shop_products.category_id
			AND
				shop_session_cart.option_id = shop_product_options.id
			AND
				shop_session_cart.product_id = shop_product_options.product_id
			AND
				shop_session_cart.session_id=%s
			GROUP BY
				shop_products.id
				,shop_session_cart.option_id
				,shop_session_cart.parent_id
			ORDER BY
				shop_session_cart.time ASC
		"
			,$db->Quote($session->session_id)
		)
	);
	$rows=$cart->GetArray();

	$country_id = $session->session->fields['delivery_country_id']+0;
	if(!$country_id && $user_session->check())
	{
		$address=$db->Execute(
			sprintf("
				SELECT
					*
				FROM
					shop_user_addresses
				WHERE
					account_id = %u
				AND
					delivery = 1
			"
				,$user_session->account_id
			)
		);
		$address = $address->FetchRow();
		if($address)
			$country_id = $address['country_id'];
	}
	if(!$country_id)
		$country_id = $config['defaultcountry_id'];

	$getcountry=$db->Execute(
		sprintf("
			SELECT
				shop_countries.name
				,shop_areas.name AS area_name
				,shop_areas.over_weight_unit
				,shop_areas.over_price
				,shop_areas.free_shipping
				,shop_countries.area_id
				,shop_countries.price
				,shop_countries.minimal_price
			FROM
				shop_countries
				,shop_areas
			WHERE
				shop_countries.id=%u
			AND
				shop_countries.area_id=shop_areas.id
		"
			,$country_id
		)
	);
	
	$country=$getcountry->fields['name'];
	$area=$getcountry->fields['area_name'];
	$area_id=$getcountry->fields['area_id'];
	
	$area_prices=$db->Execute(
		$sql = sprintf("
			SELECT
				*
			FROM
				shop_area_prices
			WHERE
				area_id = %u
			ORDER BY
				weight ASC
		"
			,$area_id
		)
	);
	
	$restricted_products=$db->Execute(
		$sql = sprintf("
			SELECT DISTINCT
				shop_session_cart.price AS cart_price
				,shop_products.id
				,shop_products.name
			FROM
			(
				shop_products
				,shop_session_cart
			)
			LEFT JOIN
				shop_product_restrictions
			ON
				shop_product_restrictions.product_id = shop_products.id
			AND
				shop_product_restrictions.area_id = %u
			LEFT JOIN
				shop_category_restrictions
			ON
				shop_category_restrictions.category_id = shop_products.category_id
			AND
				shop_category_restrictions.area_id = %u
			WHERE
				shop_products.id=shop_session_cart.product_id
			AND
				shop_session_cart.session_id=%s
			AND
			(
				shop_product_restrictions.id IS NOT NULL
			OR
				shop_category_restrictions.id IS NOT NULL
			)
			ORDER BY
				time ASC
		"
			,$area_id
			,$area_id
			,$db->Quote($session->session_id)
		)
	);
	$restricted_products = $restricted_products->GetRows();
	
	$low_stock_products=$db->Execute(
		$sql = sprintf("
			SELECT DISTINCT
				shop_session_cart.price AS cart_price
				,shop_products.id
				,shop_products.name
			FROM
			(
				shop_products
				,shop_session_cart
			)
			WHERE
				shop_products.id=shop_session_cart.product_id
			AND
				shop_session_cart.session_id=%s
			AND
			(
				shop_products.hide_stock_trigger >= shop_products.stock
			OR
				shop_products.low_stock_trigger >= shop_products.stock
			)
			ORDER BY
				time ASC
		"
			,$db->Quote($session->session_id)
		)
	);
	$low_stock_products = $low_stock_products->GetRows();

	// countries
	$countries=$db->Execute(
		sprintf("
			SELECT
				shop_countries.*
			FROM
			(
				shop_countries
				,shop_areas
			)
			LEFT JOIN
			(
				shop_product_restrictions
				,shop_session_cart
			)
			ON
				shop_product_restrictions.area_id = shop_areas.id
			AND
				shop_product_restrictions.product_id = shop_session_cart.product_id
			AND
				shop_session_cart.session_id=%s
			LEFT JOIN
			(
				shop_category_restrictions
				,shop_products
				,shop_session_cart ssc
			)
			ON
				shop_category_restrictions.area_id = shop_areas.id
			AND
				shop_category_restrictions.category_id = shop_products.category_id
			AND
				shop_products.id = ssc.product_id
			AND
				ssc.session_id=%s
			WHERE
				shop_countries.area_id = shop_areas.id
			AND
				shop_product_restrictions.id IS NULL
			AND
				shop_category_restrictions.id IS NULL
			ORDER BY
				shop_countries.name ASC
		"
			,$db->Quote($session->session_id)
			,$db->Quote($session->session_id)
		)
	);

	$vars['country']=$country;
	$vars['area']=$area;
	
	$vars['total']=0;
	$vars['total_discount_percentage']=0;
	$vars['shipping']=0;
	$vars['nitems']=0;
	$vars['weight']=0;
	$vars['packing']=0;
	$vars['discount']=0;
	$vars['multibuy_discount']=0;
	$vars['promotional_discount']=0;
	$vars['pick_up_only']=0;
	$buy3free = array();
	$nr_buy3free = 0;
	$shippable = 0;
	$product_ids = array();
	foreach($rows as $row)
	{
		$product_ids[] = $row['id'];
		$rows[$vars['nitems']]['options']=unserialize($rows[$vars['nitems']]['options']);
		$rows[$vars['nitems']]['cart_options']=unserialize($rows[$vars['nitems']]['cart_options']);
		$row['item_price']=$row['cart_price']-$row['cart_discount'];
		$rows[$vars['nitems']]['item_price']=$row['item_price'];
		$rows[$vars['nitems']]['total']=$row['cart_quantity']*$row['item_price'];
		
		if(!$row['exclude_discounts'])
			$vars['total_discount_percentage'] += $row['cart_quantity']*$row['item_price'];
		
		if($row['pick_up_only'])
			$vars['pick_up_only']++;
		
		if($row['buy_3_cheapest_free'] && !$row['promotion_b1g1f'])
		{
			for($i=0;$i<$row['cart_quantity'];$i++)
				$buy3free[] = $row['item_price'];
				
			$nr_buy3free += $row['cart_quantity'];
		}

		$vars['total']+=$row['cart_quantity']*$row['item_price'];
		if(!$row['no_shipping'] && !$row['pick_up_only'])
		{
			$vars['weight']+=$row['cart_quantity']*$row['weight'];
			$vars['packing']+=$row['cart_quantity']*$row['packing'];
			//$vars['shipping']+=$row['cart_quantity']*$row['shipping'];
			$shippable++;
		}
		$vars['discount']+=$row['cart_quantity']*$row['cart_discount'];
		
		$row['custom']=unserialize($row['custom']);
		//Comment out to make every product a gift
		if($row['gift']==1)
		{
			$hc=$db->Execute(
				sprintf("
					SELECT
						*
					FROM
						shop_countries
					WHERE
						area_id NOT IN (
							SELECT
								area_id
							FROM
								shop_product_restrictions
							WHERE
								product_id=%u
						)
					ORDER BY
						name
					ASC
				"
					,$row['id']
				)
			);
			$gift_countries=$hc->GetRows();

			for($i=0;$i<$row['cart_quantity'];$i++)
			{
				$gift['quantity']=$row['cart_quantity'];
				$custom=unserialize($row['cart_custom']);

				$gift['delivery']=$custom['delivery'][$i];

				$gift['product']=$row;
				$gift['countries']=$gift_countries;
				$vars['gifts'][]=$gift;
			}
		}
		//end of every product a gift comment out
		
		$vars['nitems']++;
	}
	sort($buy3free, SORT_NUMERIC);
	for($i=0;$i<floor($nr_buy3free/3);$i++)
		if($row = each($buy3free))
			$vars['multibuy_discount'] += $row['value'];
	$vars['total'] -= $vars['multibuy_discount'];
	
	$vars['total'] += $session->session->fields['gift_voucher'];
	
	$_SESSION['check_total'] = $vars['total'];
	$_SESSION['check_total_discount_percentage'] = $vars['total_discount_percentage'];
	
	$vars['promotional_discount_type'] = 'none';
	if($session->session->fields['discount_code'] != '')
	{
		$results=$db->Execute(
			$sql = sprintf("
			(
				SELECT
					shop_promotional_codes.*
				FROM
				(
					shop_promotional_codes
					,shop_user_promotional_codes
				)
				LEFT JOIN
					shop_user_promotional_codes supc
				ON
					supc.code_id = shop_promotional_codes.id
				AND
					supc.account_id = %u
				AND
					supc.order_id != 0
				WHERE
					shop_promotional_codes.code = %s
				AND
					shop_promotional_codes.deleted = 0
				AND
					shop_promotional_codes.suspended = 0
				AND
					shop_promotional_codes.all_users = 0
				AND
					IF(shop_promotional_codes.expiry_date, CURDATE() < shop_promotional_codes.expiry_date, 1)
				AND
					shop_user_promotional_codes.code_id = shop_promotional_codes.id
				AND
					shop_user_promotional_codes.account_id = %u
				AND
					shop_user_promotional_codes.order_id = 0
				AND
					IF(shop_promotional_codes.value_type = 'percent', %f, %f) >= shop_promotional_codes.min_order
				AND
					IF(shop_promotional_codes.gift_list_id > 0, shop_promotional_codes.gift_list_id = %u, 1)
				GROUP BY
					shop_promotional_codes.id
				HAVING
					COUNT(DISTINCT supc.order_id) < shop_promotional_codes.use_count
			)
			UNION ALL
			(
				SELECT
					shop_promotional_codes.*
				FROM
					shop_promotional_codes
				LEFT JOIN
					shop_user_promotional_codes supc
				ON
					supc.code_id = shop_promotional_codes.id
				AND
					supc.account_id = %u
				AND
					supc.order_id != 0
				WHERE
					shop_promotional_codes.code = %s
				AND
					shop_promotional_codes.deleted = 0
				AND
					shop_promotional_codes.suspended = 0
				AND
					shop_promotional_codes.all_users = 1
				AND
					IF(shop_promotional_codes.expiry_date, CURDATE() < shop_promotional_codes.expiry_date, 1)
				AND
					IF(shop_promotional_codes.value_type = 'percent', %f, %f) >= shop_promotional_codes.min_order
				AND
					IF(shop_promotional_codes.gift_list_id > 0, shop_promotional_codes.gift_list_id = %u, 1)
				GROUP BY
					shop_promotional_codes.id
				HAVING
					COUNT(DISTINCT supc.order_id) < shop_promotional_codes.use_count
			)
			"
				,$user_session->account_id
				,$db->Quote($session->session->fields['discount_code'])
				,$user_session->account_id
				,$vars['total_discount_percentage']
				,$vars['total']
				,$session->session->fields['last_gift_list_id']
				,$user_session->account_id
				,$db->Quote($session->session->fields['discount_code'])
				,$vars['total_discount_percentage']
				,$vars['total']
				,$session->session->fields['last_gift_list_id']
			)
		);
		if($row = $results->FetchRow())
		{
			$vars['promotional_discount_type'] = $row['value_type'];
			
			if($row['value_type'] == 'percent')
			{
				foreach($rows as $key=>$prod)
					if(!$prod['exclude_discounts'])
						$rows[$key]['promotional_discount'] = $row['value'] * (($prod['item_price']+0)/100);
					else
						$rows[$key]['promotional_discount'] = 0;
						
				$vars['promotional_discount'] += round($row['value'] * (($vars['total_discount_percentage']+0)/100), 2);
				//$vars['promotional_discount'] += $row['value'] * (($vars['total']+0)/100);
			}
			else
				$vars['promotional_discount'] += $row['value'];
				
			$vars['total'] -= $vars['promotional_discount'];
		}
		else
		{
			$warnings[] = array('title'=>'Discount Code', 'description'=>'The discount code you entered is invalid/expired.');
			$session->session->fields['discount_code'] = '';
			$db->Execute(
				sprintf("
					UPDATE
						shop_sessions
					SET
						discount_code=''
					WHERE
						session_id=%s
				"
					,$db->Quote($session->session_id)
				)
			);
		}
	}
	
	/*if($shippable && !($vars['total'] >= $getcountry->fields['free_shipping'] && $getcountry->fields['free_shipping'] > 0))
	{
		$last_weight = -1;
		$last_price = 0;
		$found = false;
		while($row = $area_prices->FetchRow())
		{
			if($last_weight < $vars['weight'] && $vars['weight'] <= $row['weight'])
			{
				$vars['shipping'] = $row['price'];
				$found = true;
				break;
			}
			else
			{
				$last_weight = $row['weight'];
				$last_price = $row['price'];
			}
		}
		if($last_weight < 0)
			$last_weight = 0;
			
		if(!$found)
		{
			$over_weight = $vars['weight'] - $last_weight;
			$vars['shipping'] = $last_price + $getcountry->fields['over_price']*ceil($over_weight / $getcountry->fields['over_weight_unit']);
		}
	}
	else
		$vars['shipping']=0;*/
	$vars['shipping']=0;
		
	$vars['packing']=$session->session->fields['packing'];
	
	if($vars['total']+$vars['packing']+$vars['shipping'] < 0)
		$vars['total'] = -1*($vars['packing']+$vars['shipping']);
	
	//related products
	if(count($product_ids))
	{
		$related_products=$db->Execute(
			sprintf("
				SELECT
					shop_products.id
					,shop_products.name
					,shop_products.price
					,shop_products.guid
					,shop_products.imagetype
					,shop_products.parent_id
					,shop_product_images.id image_id
					,shop_product_images.imagetype image_type
				FROM
				(
					shop_product_similar
					,shop_products
				)
				LEFT JOIN
					shop_product_images
				ON
					shop_product_images.product_id = shop_products.id
				WHERE
					shop_product_similar.product_id IN (%s)
				AND
					shop_product_similar.similar_product_id = shop_products.id
				AND
					shop_products.hidden = 0
				GROUP BY
					shop_products.id
			"
				,implode(',', $product_ids)
			)
		);
		$related_products = $related_products->GetRows();
	}
	else
		$related_products = array();
		
	//wishlist
	if($user_session->check())
	{
		$wishlist=$db->Execute(
			sprintf("
				SELECT
					shop_products.id
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
		$wishlist = $wishlist->GetRows();
	}
	else
		$wishlist = array();
	//var_export($rows);exit;
?>
