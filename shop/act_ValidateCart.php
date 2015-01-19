<?
	$ok = false;
	
	$gift_lists=$db->Execute(
		sprintf("
			SELECT DISTINCT 
				gli.list_id
			FROM
				shop_session_cart ssc
			LEFT JOIN
				gift_list_items gli
			ON
				gli.id = ssc.gift_list_item_id
			WHERE
				ssc.session_id = %s
		"
			,$db->Quote($session->session_id)
		)
	);
	if($gift_lists->RecordCount() > 1)
		return;

	if(!$session->session->fields['last_gift_list_id']+0)
	{
		$country_id = $session->session->fields['delivery_country_id']+0;
		$getcountry=$db->Execute(
			sprintf("
				SELECT
					shop_countries.name
					,shop_areas.name AS area_name
					,shop_countries.area_id
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
		$getcountry = $getcountry->FetchRow();
		if(!$getcountry)
			return;
			
		if($session->session->fields['delivery_speedtax_status'] != 'FULL')
			return;
	}
		
	/*$billing_country_id = $session->session->fields['billing_country_id']+0;
	$billing_country=$db->Execute(
		sprintf("
			SELECT
				shop_countries.name
			FROM
				shop_countries
			WHERE
				shop_countries.id=%u
		"
			,$billing_country_id
		)
	);
	$billing_country = $billing_country->FetchRow();
	if(!$billing_country)
		return;
		
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
			,$getcountry['area_id']
			,$getcountry['area_id']
			,$db->Quote($session->session_id)
		)
	);
	if($restricted_products->RecordCount())
		return;
		
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
	if($low_stock_products->RecordCount())
		return;
		
	$pick_up_products=$db->Execute(
		$sql = sprintf("
			SELECT DISTINCT
				shop_products.id
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
				shop_products.pick_up_only = 1
		"
			,$db->Quote($session->session_id)
		)
	);
	if($pick_up_products->RecordCount() && !strtotime($session->session->fields['pick_up_date']))
		return;
	*/	
	$ok = true;
?>
