<?
	if($_FILES['image']['error'] !== UPLOAD_ERR_OK && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE)
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

    $sql_update = array();
    $sql_update[] = sprintf("metadata = %s", $db->Quote(serialize($_POST['metadata'])));
    if($_FILES['image']['error'] === UPLOAD_ERR_OK)
    {
        $sql_update[] = sprintf("image_type = %s", $db->Quote(safe($img_type)));
        $sql_update[] = sprintf("ratio = %f", $img_info[0] / $img_info[1]);
    }

	$db->Execute(
		sprintf("
			UPDATE
				cms_pages_images
			SET
				%s
            WHERE
                id = %u
		"
			,implode(',', $sql_update)
			,$_REQUEST['image_id']
		)
	);

	if($_FILES['image']['error'] === UPLOAD_ERR_OK)
	{
        $image_id = $_REQUEST['image_id'];
		foreach($config['size']['page'] as $type=>$size)
		{
			if($type == 'original')
			{
                @unlink($config['path'].'images/page/'.$type.'/image_'.$image_id.'.'.$image['image_type']);
				@copy($_FILES['image']['tmp_name'], $config['path'].'images/page/'.$type.'/image_'.$image_id.'.'.$img_type);
				continue;
			}
			if($type != 'image')
            {
                $dest_file = $config['path'].'images/page/'.$type.'/image_'.$image_id.'.'.$img_type;
                $unlink_file = $config['path'].'images/page/'.$type.'/image_'.$image_id.'.'.$image['image_type'];
            }
			else
            {
                $dest_file = $config['path'].'images/page/image_'.$image_id.'.'.$img_type;
                $unlink_file = $config['path'].'images/page/image_'.$image_id.'.'.$image['image_type'];
            }
            @unlink($unlink_file);

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
		error("There was a problem whilst updating the image, please try again.  If this problem persists please contact your designated support contact","Database Error");
?>