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
				cms_pages_images
			SET
				pageid = %u
				,image_type = %s
				,ratio = %f
				,ord = IFNULL((SELECT MAX(cpi.ord)+1 FROM cms_pages_images cpi WHERE cpi.pageid=%u), 1)
		"
			,$_REQUEST['pageid']
			,$db->Quote(safe($img_type))
			,$img_info[0] / $img_info[1]
			,$_REQUEST['pageid']
		)
	);
	$image_id=$db->Insert_ID();
	
	if($image_id)
	{
		foreach($config['size']['page'] as $type=>$size)
		{
			if($type == 'original')
			{
				@copy($_FILES['image']['tmp_name'], $config['path'].'images/page/'.$type.'/image_'.$image_id.'.'.$img_type);
				continue;
			}
			if($type != 'image')
				$dest_file = $config['path'].'images/page/'.$type.'/image_'.$image_id.'.'.$img_type;
			else
				$dest_file = $config['path'].'images/page/image_'.$image_id.'.'.$img_type;
			
			try 
			{			
				$vimage = new $vcfg['vimage']['cls']($_FILES['image']['tmp_name']);
				$vimage->resize($size['x'], $size['y'])->save($dest_file);
			}
			catch(Exception $e) 
			{
				$ok = false;
				error("There was a problem resizing the image. Please try again later.");
				return;
			}
		}
	}
	
	$ok=$db->CompleteTrans();
	if(!$ok)
		error("There was a problem whilst adding the image, please try again.  If this problem persists please contact your designated support contact","Database Error");
?>