<?
	$meta_tag=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				shop_meta_tags
			WHERE
				id=%u
		"
			,$_REQUEST['tag_id']
		)
	);
	$meta_tag = $meta_tag->FetchRow();
?>