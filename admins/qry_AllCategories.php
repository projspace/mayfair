<?
	$categories=$db->Execute(
		sprintf("
			SELECT
				id
				,name
			FROM
				shop_categories
			ORDER BY
				name ASC
		"
		)
	);
	$categories = $categories->GetRows();
?>