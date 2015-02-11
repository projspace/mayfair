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
				cms_content_areas 
		"
		)
	);
	$item_count = $item_count->FetchRow();
	$item_count = $item_count['count'];
	
	$content_areas=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				cms_content_areas
			ORDER BY
				name ASC
			LIMIT
				%u, %u
		"
			,($page - 1)*$items_per_page
			,$items_per_page
		)
	);
?>