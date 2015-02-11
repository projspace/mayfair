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
				shop_product_similar
			WHERE
				product_id=%u
		"
			,$_REQUEST['product_id']
		)
	);
	$item_count = $item_count->FetchRow();
	$item_count = $item_count['count'];
	
	$similar_products=$db->Execute(
		sprintf("
			SELECT
				shop_products.*
			FROM
				shop_product_similar
				,shop_products
			WHERE
				shop_product_similar.product_id=%u
			AND
				shop_product_similar.similar_product_id = shop_products.id
			LIMIT
				%u, %u
		"
			,$_REQUEST['product_id']
			,($page - 1)*$items_per_page
			,$items_per_page
		)
	);
?>