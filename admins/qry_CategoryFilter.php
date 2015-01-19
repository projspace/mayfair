<?
	$filter=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				shop_category_filters
			WHERE
				id = %u
		"
			,$_REQUEST['filter_id']
		)
	);
	$filter = $filter->FetchRow();
	
	$filter_items = $db->Execute(
		sprintf("
			SELECT
				*
			FROM
				shop_category_filter_items
			WHERE
				filter_id=%u
			ORDER BY
				ord ASC
		"
			,$filter['id']
		)
	);
?>