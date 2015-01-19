<?
	$items=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				shop_category_box_items
			WHERE
				box_id = %u
		"
			,$_REQUEST['box_id']
		)
	);
?>