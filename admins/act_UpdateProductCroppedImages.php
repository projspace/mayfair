<?
	$ok = false;
	$errors = array();
	$img_types = array();
	foreach($image_sizes as $key=>$size)
		if($key != 'original')
		{
			if($_FILES['cropped']['error'][$key] !== UPLOAD_ERR_OK && $_FILES['cropped']['error'][$key] !== 4)
			{
				$error = '';
				switch($_FILES['cropped']['error'][$key])
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
				$errors[] = $size['description'].'('.$size['x'].' x '.$size['y'].'): '.$error;
			}
			elseif($_FILES['cropped']['error'][$key] === UPLOAD_ERR_OK)
			{
				$img_info = getimagesize($_FILES['cropped']['tmp_name'][$key]);
				if(!$img_info)
				{
					$errors[] = $size['description'].'('.$size['x'].' x '.$size['y'].'): '."The file is not a valid image. Allowed image formats: jpg, png and gif";
					continue;
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
					$errors[] = $size['description'].'('.$size['x'].' x '.$size['y'].'): '."The file is not a valid image. Allowed image formats: jpg, png and gif";
					continue;
				}
				if($size['x'] != $img_info[0] || ($size['y']+0 && $size['y'] != $img_info[1]))
				{
					$errors[] = $size['description'].'('.$size['x'].' x '.$size['y'].'): '."The image size is not ".$size['x'].' x '.$size['y'];
					continue;
				}
				$img_types[] = $img_type;
			}
		}
		
	if(count(array_unique($img_types)) == 0)
		$errors[] = 'Please upload at least one image';
		
	if(count(array_unique($img_types)) > 1)
		$errors[] = 'All uploaded images must be of the same type: '.$image['imagetype'];
	
	$img_type = $img_types[0];
	if($img_type != $image['imagetype'])
		$errors[] = 'All uploaded images must be of this type: '.$image['imagetype'];
	
	if(count($errors))
	{
		error(implode('<br />', $errors),"Upload Error");
		return;
	}	
	
	$image_id=$image['id']+0;
	if($image_id)
	{
        foreach($image_sizes as $key=>$size)
		{
			if($key == 'original')
				continue;
			if($_FILES['cropped']['error'][$key] !== UPLOAD_ERR_OK)
				continue;

			if($key != 'image')
				$dest_file = $config['path'].'images/product/'.$key.'/'.$image_id.'.'.$img_type;
			else
				$dest_file = $config['path'].'images/product/'.$image_id.'.'.$img_type;

			if(!@copy($_FILES['cropped']['tmp_name'][$key], $dest_file))
			{
				error("There was a problem copying the images. Please try again later.");
				return;
			}
            foreach((array)$size['sub_images'] as $sub_key=>$sub_size)
            {
                if($sub_key != 'image')
                    $dest_file = $config['path'].'images/product/'.$sub_key.'/'.$image_id.'.'.$img_type;
                else
                    $dest_file = $config['path'].'images/product/'.$image_id.'.'.$img_type;

                try
                {
                    $vimage = new $vcfg['vimage']['cls']($_FILES['cropped']['tmp_name'][$key]);
                    $vimage->resize($sub_size['x']+0, $sub_size['y']+0)->save($dest_file);
                }
                catch (Exception $e)
                {
                    $ok = false;
                    error("There was a problem resizing the image. Please try again later.");
                    return;
                }
            }
		}
	}
	
	$ok = true;
?>