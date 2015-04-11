<?
	$category=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				shop_categories
			WHERE 
				id = %u
		"
			,$_REQUEST['category_id']
		)
	);
	$category = $category->FetchRow();
	
	$products=$db->Execute(
		sprintf("
		(
			SELECT DISTINCT
				shop_categories.id
				,shop_categories.name
				,shop_categories.lft
				,shop_categories.trail
				,'category' type
			FROM
				shop_categories
			WHERE
				shop_categories.lft >= %u
			AND
				shop_categories.rgt <= %u
		)
		UNION ALL
		(
			SELECT DISTINCT
				shop_products.id
				,shop_products.name
				,shop_categories.lft
				,shop_categories.trail
				,'product' type
			FROM
				shop_products
			LEFT JOIN
				shop_categories
			ON
				shop_products.category_id = shop_categories.id
			WHERE
				shop_products.id > 1
			AND
				shop_categories.lft >= %u
			AND
				shop_categories.rgt <= %u
		)
		ORDER BY
			lft ASC
		"
			,$category['lft']
			,$category['rgt']
			,$category['lft']
			,$category['rgt']
		)
	);
?>