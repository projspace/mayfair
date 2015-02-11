<?
	// prices
	$lowest_price=$db->Execute(
		$sql = sprintf("
			SELECT
				MIN(IF(shop_products.vat, shop_products.price*(100+%f)/100, shop_products.price)) min
			FROM
				shop_products
			LEFT JOIN
				shop_product_tags
			ON
				shop_product_tags.product_id = shop_products.id
			LEFT JOIN
				shop_product_filters
			ON
				shop_product_filters.product_id = shop_products.id
			WHERE
				shop_products.id > 1
		"
			,VAT
		)
	);
	$lowest_price = $lowest_price->FetchRow();
	$lowest_price = floor($lowest_price['min']);
	
	$highest_price=$db->Execute(
		$sql = sprintf("
			SELECT
				MAX(IF(shop_products.vat, shop_products.price*(100+%f)/100, shop_products.price)) max
			FROM
				shop_products
			LEFT JOIN
				shop_product_tags
			ON
				shop_product_tags.product_id = shop_products.id
			LEFT JOIN
				shop_product_filters
			ON
				shop_product_filters.product_id = shop_products.id
			WHERE
				shop_products.id > 1
		"
			,VAT
		)
	);
	$highest_price = $highest_price->FetchRow();
	$highest_price = ceil($highest_price['max']);
	
	// filters
	$sql_default = array();
	$sql_default[] = sprintf("shop_products.id > 1");
	
	$results=$db->Execute(
		$sql = sprintf("
			SELECT DISTINCT
				shop_filters.*
			FROM
			(
				shop_products
				,shop_product_filters
				,shop_filters
			)
			WHERE
				shop_products.id = shop_product_filters.product_id
			AND
				shop_filters.id = shop_product_filters.filter_id
			AND
				%s
			ORDER BY
				shop_filters.type ASC
				,shop_filters.name ASC
		"
			,implode(' AND ',$sql_default)
		)
	);
	$filters = array();
	while($row = $results->FetchRow())
		$filters[$row['type']][] = $row;
?>