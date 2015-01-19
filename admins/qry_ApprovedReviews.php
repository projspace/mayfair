<?
	$page = $_REQUEST['page']+0;
	if($page <= 0)
		$page = 1;
	$items_per_page = 10;
	
	$item_count=$db->Execute(
		sprintf("
			SELECT
				COUNT(*) count
			FROM
				shop_product_reviews
			WHERE
				status='approved'
		"
		)
	);
	$item_count = $item_count->FetchRow();
	$item_count = $item_count['count'];
	
	$reviews=$db->Execute(
		sprintf("
			SELECT
				shop_product_reviews.*
				,shop_products.name product
				,shop_products.category_id
			FROM
				shop_product_reviews
			LEFT JOIN
				shop_products
			ON
				shop_product_reviews.product_id = shop_products.id
			WHERE
				shop_product_reviews.status='approved'
			ORDER BY
				shop_product_reviews.posted ASC
			LIMIT
				%u, %u
		"
			,($page - 1)*$items_per_page
			,$items_per_page
		)
	);
?>