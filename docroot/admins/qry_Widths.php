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
				shop_widths
		"
		)
	);
	$item_count = $item_count->FetchRow();
	$item_count = $item_count['count'];
	
	$widths=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				shop_widths
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