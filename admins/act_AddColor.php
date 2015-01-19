<?
	if($_FILES['image']['error'] !== UPLOAD_ERR_OK && $_FILES['image']['error'] !== 4)
	{
		$error = '';
		switch($_FILES['image']['error'])
		{
			case 1:
			case 2:
				$error = "The uploaded file is too big";
			break;
			case 3:
				$error = "The uploaded file was only partially uploaded.";
			break;
			case 4:
				$error = "No file was uploaded.";
			break;
			case 6:
				$error = "System error.";
			break;
			case 7:
				$error = "System error .";
			break;
			default:
				$error = "Unknown upload error";
			break;
		}
		error($error,"Upload Error");
		return;
	}
	
	if($_FILES['image']['error'] === UPLOAD_ERR_OK)
	{
		$img_info = getimagesize($_FILES['image']['tmp_name']);
		if(!$img_info)
		{
			error("The file is not a valid image. Allowed image formats: jpg, png and gif","Upload Error");
			return;
		}
		
		if($img_info[0] != 20 || $img_info[1] != 20)
		{
			error("The image must be 20 x 20.","Upload Error");
			return;
		}
		
		$img_type = false;
		switch($img_info[2])
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
		}
		if(!$img_type)
		{
			error("The file is not a valid image. Allowed image formats: jpg, png and gif","Upload Error");
			return;
		}
	}
	
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();

	$db->Execute(
		sprintf("
			INSERT INTO
				shop_colors
			SET
				name = %s
				,code = %s
				,hexa = %s
				,ord = IFNULL((SELECT MAX(sw.ord)+1 FROM shop_colors sw), 1)
		"
			,$db->Quote(safe($_POST['name']))
			,$db->Quote(safe($_POST['code']))
			,$db->Quote(safe($_POST['hexa']))
		)
	);
	$color_id=$db->Insert_ID();
	
	if($_FILES['image']['error'] === UPLOAD_ERR_OK)
	{
		if(!@move_uploaded_file($_FILES['image']['tmp_name'], $config['path'].'images/colors/'.$color_id.'.'.$img_type))
		{
			error("There was a problem whilst saving the image. Please try again.","Upload Error");
			return;
		}
		
		$db->Execute(
			sprintf("
				UPDATE
					shop_colors
				SET
					image_type = %s
				WHERE
					id = %u
			"
				,$db->Quote($img_type)
				,$color_id
			)
		);
	}
	
	$ok=$db->CompleteTrans();
	if(!$ok)
		error("There was a problem whilst adding the product color, please try again.  If this problem persists please contact your designated support contact","Database Error");
?>