<?
	$brands=$db->Execute(
		sprintf("
			SELECT
				shop_brands.*
			FROM
				shop_brands
			ORDER BY
				shop_brands.name ASC
		"
		)
	);
?>