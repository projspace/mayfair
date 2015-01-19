<?
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();
	
	$imageinfo=$db->Execute(
		sprintf("
			SELECT
				id
				,imagetype
			FROM
				shop_product_images
			WHERE
				id=%u
		"
			,$_POST['imageid']
		)
	);
	$row=$imageinfo->FetchRow();
	unlink("../images/product/image{$row['id']}.{$row['imagetype']}");
	unlink("../images/product/thumb/image{$row['id']}.{$row['imagetype']}");
	unlink("../images/product/medium/image{$row['id']}.{$row['imagetype']}");
	unlink("../images/product/original/image{$row['id']}.{$row['imagetype']}");
	$db->Execute(
		sprintf("
			DELETE FROM
				shop_product_images
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