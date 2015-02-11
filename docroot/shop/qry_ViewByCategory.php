<?
	$categories=$db->Execute(
		sprintf("
			SELECT
				shop_categories.*
			FROM
				shop_categories
            WHERE
                id > 1
            AND
                parent_id > 1
            AND
                hidden = 0
            ORDER BY
                name ASC
		"
		)
	);
?>
