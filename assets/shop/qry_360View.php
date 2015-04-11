<?
	$images=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				shop_product_360_images
			WHERE
				product_id=%u
			ORDER BY
				id ASC
		"
			,$product_id
		)
	);
?>
