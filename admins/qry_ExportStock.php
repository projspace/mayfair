<?
	$db->Execute(
		$sql = sprintf("
			CREATE TEMPORARY TABLE 
				temp_stock
			SELECT
				shop_products.id product_id
			FROM
				shop_products
			LEFT JOIN
				shop_product_options
			ON
				shop_product_options.product_id = shop_products.id
			WHERE
				shop_products.id > 1
			AND
				shop_products.hidden = 0
			AND
				shop_products.parent_id = 0
			AND
				shop_products.category_id > 0
			GROUP BY 
				shop_products.id
			HAVING
				SUM(shop_product_options.quantity) > 0
		"
		)
	);
	
	$stock=$db->Execute(
		$sql = sprintf("
			SELECT DISTINCT
				shop_products.name
				,shop_products.code
				,shop_colors.name color
				,shop_widths.name width
				,shop_sizes.name size
				,shop_product_options.quantity
				,shop_product_options.upc_code
				,temp_stock.product_id IS NULL hidden
			FROM
				shop_products
			LEFT JOIN
				shop_product_options
			ON
				shop_products.id = shop_product_options.product_id
			LEFT JOIN
				shop_colors
			ON
				shop_colors.id = shop_product_options.color_id
			LEFT JOIN
				shop_widths
			ON
				shop_widths.id = shop_product_options.width_id
			LEFT JOIN
				shop_sizes
			ON
				shop_sizes.id = shop_product_options.size_id
			LEFT JOIN
				temp_stock
			ON
				shop_products.id = temp_stock.product_id
			WHERE
				shop_products.id > 1
		"
		)
	);
?>