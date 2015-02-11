<?
	$results=$db->Execute(
		$sql = sprintf("
			SELECT
				shop_category_filter_items.*
				,shop_category_filters.id filter_id
				,shop_category_filters.name filter_name
				,shop_category_filters.type
			FROM
				shop_category_filters
			LEFT JOIN
				shop_category_filter_items
			ON
				shop_category_filters.id = shop_category_filter_items.filter_id
			WHERE
				shop_category_filters.category_id = %u
			ORDER BY
				shop_category_filters.ord ASC
				,shop_category_filter_items.ord ASC
		"
			,$_REQUEST['category_id']
		)
	);
	$filters = array();
	while($row = $results->FetchRow())
	{
		if(!isset($filters[$row['filter_id']]))
			$filters[$row['filter_id']] = array('id'=>$row['filter_id'], 'name'=>$row['filter_name'], 'type'=>$row['type'], 'items'=>array());
		if($row['id'])
			$filters[$row['filter_id']]['items'][] = $row;
	}
?>