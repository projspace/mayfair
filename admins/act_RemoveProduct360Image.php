<?
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();
	
	$imageinfo=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				shop_product_360_images
			WHERE
				id=%u
		"
			,$_POST['imageid']
		)
	);
	$row=$imageinfo->FetchRow();
	unlink("../images/product/360_view/{$row['id']}.{$row['image_type']}");
	$db->Execute(
		sprintf("
			DELETE FROM
				shop_product_360_images
			WHERE
				id=%u
		"
			,$_POST['imageid']
		)
	);
	
	$ok=$db->CompleteTrans();
	if(!$ok)
    	error("There was a problem whilst removing the image, please try again.  If this persists please notify your designated support contact","Database Error");
?>