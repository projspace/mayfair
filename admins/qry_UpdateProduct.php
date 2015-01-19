<?
	$product_vars=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				shop_product_vars
			WHERE
				product_id=%u
		"
			,$_REQUEST['product_id']
		)
	);

	$areas=$db->Execute(
		sprintf("
			SELECT
				shop_areas.id
				,shop_areas.name
				,shop_product_restrictions.id AS restriction_id
			FROM
				shop_areas
			LEFT JOIN
				shop_product_restrictions
			ON
				shop_product_restrictions.area_id=shop_areas.id
			AND
				shop_product_restrictions.product_id=%u
			ORDER BY
				shop_areas.name ASC
		"
			,$_REQUEST['product_id']
		)
	);
	
	$product=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				shop_products
			WHERE
				id=%u
		"
			,$_REQUEST['product_id']
		)
	);
	$product=$product->FetchRow();
?>