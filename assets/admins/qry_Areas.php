<?
	$areas=$db->Execute("
		SELECT
			shop_areas.*
			,GROUP_CONCAT(shop_countries.name ORDER BY shop_countries.name ASC SEPARATOR ', ') countries
		FROM
			shop_areas
		LEFT JOIN
			shop_countries
		ON
			shop_countries.area_id = shop_areas.id
		GROUP BY
			shop_areas.id
		ORDER BY
			shop_areas.name	ASC
	");
?>