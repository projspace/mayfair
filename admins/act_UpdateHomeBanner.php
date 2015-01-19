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
		
		if($img_info[0] != 733 || $img_info[1] != 765)
		{
			error("The image must be 733 x 765.","Upload Error");
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
			UPDATE
				cms_home_banners
			SET
				url = %s
				,label = %s
				,description = %s
			WHERE
				id = %u
		"
			,$db->Quote(safe($_POST['url']))
			,$db->Quote(safe($_POST['label']))
			,$db->Quote(safe($_POST['description']))
			,$_REQUEST['banner_id']
		)
	);

	if($_FILES['image']['error'] === UPLOAD_ERR_OK)
	{
		if(file_exists($filename = $config['path'].'images/home_banners/'.$home_banner['id'].'.'.$home_banner['image_type']))
			@unlink($filename);
			
		if(!@move_uploaded_file($_FILES['image']['tmp_name'], $config['path'].'images/home_banners/'.($_REQUEST['banner_id']+0).'.'.$img_type))
		{
			error("There was a problem whilst saving the image. Please try again.","Upload Error");
			return;
		}
		
		$db->Execute(
			sprintf("
				UPDATE
					cms_home_banners
				SET
					image_type = %s
				WHERE
					id = %u
			"
				,$db->Quote($img_type)
				,$_REQUEST['banner_id']
			)
		);
	}
	
	$ok=$db->CompleteTrans();
	if(!$ok)
		error("There was a problem whilst updating the banner, please try again.  If this problem persists please contact your designated support contact","Database Error");
?>