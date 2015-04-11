<?
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
?>