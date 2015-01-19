<?
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();
	
	$db->Execute(
		$sql = sprintf("
			UPDATE
				shop_product_images
			SET
				color_id = %u
			WHERE
				id = %u
		"
			,$_POST['color_id']
			,$_POST['image_id']
		)
	);

	$ok=$db->CompleteTrans();
	if(!$ok)
		error("There was a problem whilst updating the image color, please try again.  If this problem persists please contact your designated support contact","Database Error");
?>