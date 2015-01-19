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
				cms_pages_images
			WHERE
				pageid=%u
		"
			,$_REQUEST['pageid']
		)
	);
	$item_count = $item_count->FetchRow();
	$item_count = $item_count['count'];
	
	$images=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				cms_pages_images
			WHERE
				pageid=%u
			ORDER BY
				ord ASC
			LIMIT
				%u, %u
		"
			,$_REQUEST['pageid']
			,($page - 1)*$items_per_page
			,$items_per_page
		)
	);
?>