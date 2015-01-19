<?
	$redirect_info = unserialize(stripslashes($_REQUEST['redirect_info']));
	
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();
	
	$image_info = array();
	$image_ids = array();
	foreach($redirect_info as $info)
	{
		switch($info['image_info'][2])
		{
			case IMAGETYPE_JPEG:
				$img_type = "jpg";
				break;
			case IMAGETYPE_GIF:
				$img_type = "gif";
				break;
			case IMAGETYPE_PNG:
				$img_type = "png";
				break;
			default:
				error("The file is not a valid image. Allowed image formats: jpg, png and gif");
				return;
				break;
		}
		
		$db->Execute(
			sprintf("
				UPDATE
					shop_products
				SET
					imagetype = %s
				WHERE
					id = %u
			"
				,$db->Quote($img_type)
				,$info['id']
			)
		);
		continue;
	}
	$ok=$db->CompleteTrans();
	if(!$ok)
		error("There was a problem whilst updating the product image, please try again.  If this problem persists please contact your designated support contact","Database Error");
?>