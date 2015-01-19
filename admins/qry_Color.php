<?
	$color=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				shop_colors
			WHERE
				id = %u
		"
			,$_REQUEST['color_id']
		)
	);
	$color = $color->FetchRow();
?>