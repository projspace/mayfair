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
				shop_category_filters
			WHERE
				category_id = %u
		"
			,$_REQUEST['category_id']
		)
	);
	$item_count = $item_count->FetchRow();
	$item_count = $item_count['count'];
	
	$filters=$db->Execute(
		sprintf("
			SELECT DISTINCT
				*
			FROM
				shop_category_filters
			WHERE
				category_id = %u
			ORDER BY
				ord ASC
			LIMIT
				%u, %u
		"
			,$_REQUEST['category_id']
			,($page - 1)*$items_per_page
			,$items_per_page
		)
	);
?>