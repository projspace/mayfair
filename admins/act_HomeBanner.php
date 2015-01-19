<?
	$alert = array();
	
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();

	$db->Execute(
		sprintf("
			UPDATE
				cms_variables
			SET
				value=%s
			WHERE
				name = 'home_banner_url'
		"
			,$db->Quote($_POST['url'])
		)
	);
	
	$db->Execute(
		sprintf("
			UPDATE
				cms_variables
			SET
				value=%s
			WHERE
				name = 'home_banner_type'
		"
			,$db->Quote($_POST['type'])
		)
	);
	
	if($_FILES['image']['error'] == UPLOAD_ERR_OK)
	{
		$img_info = getimagesize($_FILES['image']['tmp_name']);
		if(!$img_info)
		{
			$alert[] = "The file is not a valid image. Allowed image formats: jpg, png and gif";
		}
		else
		{
			if($img_info[0] != 733 || $img_info[1] != 823)
				$alert[] = "The image must be 733 x 823.";
			else
			{
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
					default:
						$alert[] = "The file is not a valid image. Allowed image formats: jpg, png and gif";
						break;
				}
				if($img_type)
				{
					if(!move_uploaded_file($_FILES['image']['tmp_name'], $config['path'].'images/home_banner.'.$img_type))
						$alert[] = "There was a problem whilst saving the image. Please try again.";
					else
						$db->Execute(
							sprintf("
								UPDATE
									cms_variables
								SET
									value=%s
								WHERE
									name = 'home_banner_image_type'
							"
								,$db->Quote($img_type)
							)
						);
				}
			}
		}
	}
	
	
	$ok=$db->CompleteTrans();
	if(!$ok)
		error("There was a problem whilst updating the home banner, please try again.  If this problem persists please contact your designated support contact","Database Error");
	else
	{
		if(count($alert))
		{
			$ok = false;
			alert("The home banner has been updated. However:<br />".implode("<br />\n", $alert), "Warning");
			alertRender();
		}
		$_SESSION['alert'] = 'Banner updated.';
	}
?>