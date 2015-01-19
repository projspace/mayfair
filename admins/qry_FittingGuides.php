<?
	$fitting_count=$db->Execute(
		sprintf("
			SELECT
				MAX(shop_fitting_guides.ord) row_count
				,MAX(shop_fitting_guide_columns.ord) column_count
			FROM
				shop_fitting_guides
				,shop_fitting_guide_columns
		"
		)
	);
	$fitting_count = $fitting_count->FetchRow();
	
	$results=$db->Execute(
		sprintf("
			SELECT
				shop_fitting_guides.id row_id
				,shop_fitting_guides.ord row_index
				,shop_fitting_guides.name row_name
				,shop_fitting_guide_columns.id column_id
				,shop_fitting_guide_columns.ord column_index
				,shop_fitting_guide_columns.name column_name
				,shop_fitting_guide_sizes.id size_id
				,shop_fitting_guide_sizes.size
			FROM
			(
				shop_fitting_guides
				,shop_fitting_guide_columns
			)
			LEFT JOIN
				shop_fitting_guide_sizes
			ON
				shop_fitting_guide_sizes.guide_id = shop_fitting_guides.id
			AND
				shop_fitting_guide_sizes.column_id = shop_fitting_guide_columns.id
			ORDER BY
				shop_fitting_guides.ord ASC
				,shop_fitting_guide_columns.ord ASC
		"
		)
	);
	$fitting_guides = array();
	$fitting_guides_rows = array();
	$fitting_guides_columns = array();
	while($row = $results->FetchRow())
	{
		$fitting_guides_rows[$row['row_index']] = $row;
		$fitting_guides_columns[$row['column_index']] = $row;
		$fitting_guides[$row['row_index'].','.$row['column_index']] = $row;
	}
?>