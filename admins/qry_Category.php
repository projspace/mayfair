<?
	$category=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				shop_categories
			WHERE 
				id = %u
		"
			,$_REQUEST['category_id']
		)
	);
	$category = $category->FetchRow();
	
	$fields=explode("\n",$category['vars']);

	$areas=$db->Execute(
		sprintf("
			SELECT
				shop_areas.id
				,shop_areas.name
				,shop_category_restrictions.id AS restriction_id
			FROM
				shop_areas
			LEFT JOIN
				shop_category_restrictions
			ON
				shop_category_restrictions.area_id=shop_areas.id
			AND
				shop_category_restrictions.category_id=%u
			ORDER BY
				shop_areas.name ASC
		"
			,$_REQUEST['category_id']
		)
	);
	
	$child_count=$db->Execute(
		sprintf("
			SELECT
				COUNT(*) count
			FROM
				shop_categories
			WHERE 
				parent_id = %u
		"
			,$category['id']
		)
	);
	$child_count = $child_count->FetchRow();
	$child_count = $child_count['count'];
	
	$results=$db->Execute(
		sprintf("
			SELECT
				guide_id
			FROM
				shop_category_fitting_guides
			WHERE 
				category_id = %u
		"
			,$category['id']
		)
	);
	$category_fitting_guides = array();
	while($row = $results->FetchRow())
		$category_fitting_guides[$row['guide_id']] = 1;
		
	$results=$db->Execute(
		sprintf("
			SELECT
				column_id
			FROM
				shop_category_fitting_guide_columns
			WHERE 
				category_id = %u
		"
			,$category['id']
		)
	);
	$category_fitting_guide_columns = array();
	while($row = $results->FetchRow())
		$category_fitting_guide_columns[$row['column_id']] = 1;
?>
