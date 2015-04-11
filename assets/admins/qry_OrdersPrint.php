<?
	$orders=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				shop_orders
			WHERE
				processed = 0
			ORDER BY
				time DESC
		"
		)
	);
?>