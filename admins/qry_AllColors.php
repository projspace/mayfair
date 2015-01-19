<?
	$colors=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				shop_colors
			ORDER BY
				ord ASC
		"
		)
	);
	$colors = $colors->GetRows();
?>