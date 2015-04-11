<?
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
	
	$product_tags = $db->Execute(
		sprintf("
			SELECT DISTINCT
				shop_meta_tags.*
			FROM
				shop_meta_tags
				,shop_product_tags
			WHERE
				shop_product_tags.tag_id = shop_meta_tags.id
			AND
				shop_product_tags.product_id=%u
		"
			,$product['id']
		)
	);
	
	$results = $db->Execute(
		sprintf("
			SELECT
				*
			FROM
				shop_product_filters
			WHERE
				product_id=%u
		"
			,$product['id']
		)
	);
	$filter_ids = array();
	while($row = $results->FetchRow())
		$filter_ids[$row['filter_id']] = 1;
		
	$product_warnings = $db->Execute(
		sprintf("
			SELECT
				*
			FROM
				shop_product_warnings
			WHERE
				product_id=%u
			ORDER BY
				`trigger` ASC
		"
			,$product['id']
		)
	);
	
	$product_options = $db->Execute(
		sprintf("
			SELECT DISTINCT
				shop_product_options.*
				,shop_sizes.name size
				,shop_widths.name width
				,shop_colors.name color
			FROM
				shop_product_options
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
				shop_product_options.product_id=%u
			ORDER BY
				upc_code ASC
		"
			,$product['id']
		)
	);
	
	//fitting guides
	$results=$db->Execute(
		sprintf("
			SELECT
				guide_id
			FROM
				shop_category_fitting_guides
			WHERE 
				category_id = %u
		"
			,$product['category_id']
		)
	);
	$category_fitting_guides = array();
	while($row = $results->FetchRow())
		$category_fitting_guides[$row['guide_id']] = 1;
		
	$results=$db->Execute(
		sprintf("
			SELECT
				column_id
			FROM
				shop_category_fitting_guide_columns
			WHERE 
				category_id = %u
		"
			,$product['category_id']
		)
	);
	$category_fitting_guide_columns = array();
	while($row = $results->FetchRow())
		$category_fitting_guide_columns[$row['column_id']] = 1;
		
	$results=$db->Execute(
		sprintf("
			SELECT
				column_id
			FROM
				shop_product_fitting_guide_columns
			WHERE 
				product_id = %u
		"
			,$product['id']
		)
	);
	$product_fitting_guide_columns = array();
	while($row = $results->FetchRow())
		$product_fitting_guide_columns[$row['column_id']] = 1;
?>