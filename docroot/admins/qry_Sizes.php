<?
	$page = $_REQUEST['page']+0;
	if($page <= 0)
		$page = 1;
	$items_per_page = 10000000;
	
	$item_count=$db->Execute(
		sprintf("
			SELECT
				COUNT(*) count
			FROM
				shop_sizes
		"
		)
	);
	$item_count = $item_count->FetchRow();
	$item_count = $item_count['count'];
	
	$sizes=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				shop_sizes
			ORDER BY
				ord ASC
			LIMIT
				%u, %u
		"
			,($page - 1)*$items_per_page
			,$items_per_page
		)
	);
?>