<?
	if($_FILES['image']['error'] !== UPLOAD_ERR_OK)
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
	
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();

	$db->Execute(
		sprintf("
			INSERT INTO
				cms_home_banners
			SET
				url = %s
				,label = %s
				,description = %s
				,image_type = %s
				,ord = IFNULL((SELECT MAX(chb.ord)+1 FROM cms_home_banners chb), 1)
		"
			,$db->Quote(safe($_POST['url']))
			,$db->Quote(safe($_POST['label']))
			,$db->Quote(safe($_POST['description']))
			,$db->Quote($img_type)
		)
	);
	$banner_id=$db->Insert_ID();

	if(!@move_uploaded_file($_FILES['image']['tmp_name'], $config['path'].'images/home_banners/'.$banner_id.'.'.$img_type))
	{
		error("There was a problem whilst saving the image. Please try again.","Upload Error");
		return;
	}
	
	$ok=$db->CompleteTrans();
	if(!$ok)
		error("There was a problem whilst adding the banner, please try again.  If this problem persists please contact your designated support contact","Database Error");
?>