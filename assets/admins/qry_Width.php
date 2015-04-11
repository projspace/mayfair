<?
	$width=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				shop_widths
			WHERE
				id = %u
		"
			,$_REQUEST['width_id']
		)
	);
	$width = $width->FetchRow();
?>