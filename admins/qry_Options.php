<?
	$sizes=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				shop_sizes
			ORDER BY
				ord ASC
		"
		)
	);
	
	$widths=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				shop_widths
			ORDER BY
				ord ASC
		"
		)
	);
	
	$colors=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				shop_colors
			ORDER BY
				name ASC
		"
		)
	);
?>