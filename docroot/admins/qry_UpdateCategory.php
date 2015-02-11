<?
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
			,$_POST['category_id']
		)
	);
?>