<?
	$item=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				shop_category_box_items
			WHERE
				id = %u
		"
			,$_REQUEST['item_id']
		)
	);
	$item = $item->FetchRow();
?>