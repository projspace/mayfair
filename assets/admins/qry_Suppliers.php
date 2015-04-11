<?
	$suppliers=$db->Execute("
		SELECT
			shop_suppliers.*
			,shop_countries.name AS country_name
		FROM
			shop_suppliers
		LEFT JOIN
			shop_countries
		ON
			shop_suppliers.country_id=shop_countries.id
		ORDER BY
			shop_suppliers.name
		ASC
	");
?>