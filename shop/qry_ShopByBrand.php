<?
	$brands=$db->Execute(
		sprintf("
			SELECT
				shop_brands.*
			FROM
				shop_brands
            WHERE
                hidden = 0
            ORDER BY
                name ASC
		"
		)
	);
?>
