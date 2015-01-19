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
?>