<?
	$all_products=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				shop_products
			WHERE
				id > 1
			ORDER BY
				name ASC
		"
		)
	);
?>