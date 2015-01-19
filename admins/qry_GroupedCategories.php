<?
	$categories=$db->Execute(
		sprintf("
			SELECT
				sc1.id
				,sc1.name
				,sc1.trail
				,COUNT(sc2.id) children
			FROM
				shop_categories sc1
			LEFT JOIN
				shop_categories sc2
			ON
				sc2.parent_id = sc1.id
			GROUP BY
				sc1.id
			HAVING
				COUNT(sc2.id) = 0
			ORDER BY
				sc1.lft ASC
		"
		)
	);
?>