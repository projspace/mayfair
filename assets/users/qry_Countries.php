<?
	$countries=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				shop_countries
			ORDER BY
				name
			ASC
		"
		)
	);
?>