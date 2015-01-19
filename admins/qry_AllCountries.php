<?
	$countries=$db->Execute(
		sprintf("
			SELECT
				shop_countries.id
				,shop_countries.name
			FROM
				shop_countries
			ORDER BY
				shop_countries.name ASC
		"
		)
	);
	$countries = $countries->GetRows();
?>