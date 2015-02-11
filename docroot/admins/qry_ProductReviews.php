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
				product_id=%u
		"
			,$_REQUEST['product_id']
		)
	);
	$item_count = $item_count->FetchRow();
	$item_count = $item_count['count'];
	
	$reviews=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				shop_product_reviews
			WHERE
				product_id=%u
			ORDER BY
				status ASC
				,posted DESC
			LIMIT
				%u, %u
		"
			,$_REQUEST['product_id']
			,($page - 1)*$items_per_page
			,$items_per_page
		)
	);
?>