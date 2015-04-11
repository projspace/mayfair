<?
	$image=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				cms_pages_images
			WHERE
				id = %u
		"
			,$_REQUEST['image_id']
		)
	);
	$image = $image->FetchRow();
    $image['metadata'] = unserialize($image['metadata']);
?>