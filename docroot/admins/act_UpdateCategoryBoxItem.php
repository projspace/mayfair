<?
	switch($item['type'])
	{
		case 'big1':
			$size[0] = 490;
			$size[1] = 430;
			break;
		case 'small':
			$size[0] = 242;
			$size[1] = 430;
			break;
		case 'big2':
			$size[0] = 366;
			$size[1] = 496;
			break;
		case 'small1':
			$size[0] = 366;
			$size[1] = 196;
			break;
		case 'small2':
			$size[0] = 366;
			$size[1] = 296;
			break;
		default:
			{
				error("Unknown box image type.","Form Error");
				return;
			}
			break;
	}
	
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
		
		if($img_info[0] != $size[0] || $img_info[1] != $size[1])
		{
			error("The image must be {$size[0]} x {$size[1]}.","Upload Error");
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

	$link = safe($_POST['link']);
	if($link != '')
	{
		$link = explode('_', $link);
		$link_id = $link[1]+0;
		$link_type = safe($link[0]);
	}
	else
	{
		$link_id = 0;
		$link_type = '';
	}
	
	//var_dump($link_id, $link_type);exit;
	
	$db->Execute(
		sprintf("
			UPDATE
				shop_category_box_items
			SET
				label = %s
				,title = %s
				,small_title = %s
				,link_id = %u
				,link_type = %s
			WHERE
				id = %u
		"
			,$db->Quote(safe($_POST['label']))
			,$db->Quote(safe($_POST['title']))
			,$db->Quote(safe($_POST['small_title']))
			,$link_id
			,$db->Quote($link_type)
			,$_REQUEST['item_id']
		)
	);

	if($_FILES['image']['error'] === UPLOAD_ERR_OK)
	{
		if(file_exists($filename = $config['path'].'images/box_items/'.$item['id'].'.'.$item['image_type']))
			@unlink($filename);
			
		if(!@move_uploaded_file($_FILES['image']['tmp_name'], $config['path'].'images/box_items/'.($_REQUEST['item_id']+0).'.'.$img_type))
		{
			error("There was a problem whilst saving the image. Please try again.","Upload Error");
			return;
		}
		
		$db->Execute(
			sprintf("
				UPDATE
					shop_category_box_items
				SET
					image_type = %s
				WHERE
					id = %u
			"
				,$db->Quote($img_type)
				,$_REQUEST['item_id']
			)
		);
	}
	
	$ok=$db->CompleteTrans();
	if(!$ok)
		error("There was a problem whilst updating the box item, please try again.  If this problem persists please contact your designated support contact","Database Error");
?>