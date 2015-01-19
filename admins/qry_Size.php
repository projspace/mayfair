<?
	$size=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				shop_sizes
			WHERE
				id = %u
		"
			,$_REQUEST['size_id']
		)
	);
	$size = $size->FetchRow();
?>