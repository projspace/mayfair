<?
	$block=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				shop_deep_linking
			WHERE
				id = %u
		"
			,$_REQUEST['block_id']
		)
	);
	$block = $block->FetchRow();
?>