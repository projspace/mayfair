<?
	$image=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				shop_product_images
			WHERE
				product_id=%u
			AND
				id = %u
		"
			,$_REQUEST['product_id']
			,$_REQUEST['image_id']
		)
	);
	$image = $image->FetchRow();
?>