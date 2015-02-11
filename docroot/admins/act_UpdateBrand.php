<?
	if($_FILES['content_image']['error'] === UPLOAD_ERR_OK)
	{
		$img_info = getimagesize($_FILES['content_image']['tmp_name']);
		if(!$img_info)
		{
			error("The file is not a valid image. Allowed image formats: jpg, png and gif","Upload Error");
			return;
		}

		if($img_info[0] != 373)
        {
            error("The image must have with 373px.","Upload Error");
            return;
        }
	}

	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();
	
	$db->Execute(
		sprintf("
			UPDATE
				shop_brands
			SET
				name=%s
				,content=%s
				,hidden=%u
				,content_visible=%u
			WHERE
				id=%u
		"
			,$db->Quote($_POST['name'])
			,$db->Quote($_POST['content'][0])
			,$_POST['hidden']
			,$_POST['content_visible']
			,$_POST['brand_id']
		)
	);

    if($_POST['delete']=="on")
	{
		$get=$db->Execute(
			sprintf("
				SELECT
					id
					,imagetype
				FROM
					shop_brands
				WHERE
					id=%u
			"
				,$_POST['brand_id']
			)
		);

		$row=$get->FetchRow();
		if($row['imagetype']!="")
		{
			unlink("../images/brand/".$row['id'].".".$row['imagetype']);
			unlink("../images/brand/thumbs/".$row['id'].".".$row['imagetype']);
		}

		$db->Execute(
			sprintf("
				UPDATE
					shop_brands
				SET
					imagetype=''
				WHERE
					id=%u
			"
				,$_POST['brand_id']
			)
		);
	}

	if($_POST['content_delete']=="on")
	{
		$get=$db->Execute(
			sprintf("
				SELECT
					id
					,content_imagetype
				FROM
					shop_brands
				WHERE
					id=%u
			"
				,$_POST['brand_id']
			)
		);

		$row=$get->FetchRow();
		if($row['content_imagetype']!="")
		{
			unlink("../images/brand/content/".$row['id'].".".$row['content_imagetype']);
		}

		$db->Execute(
			sprintf("
				UPDATE
					shop_brands
				SET
					content_imagetype=''
				WHERE
					id=%u
			"
				,$_POST['brand_id']
			)
		);
	}

	if($_FILES['image']['error'] === UPLOAD_ERR_OK)
	{
		$type=$resize->resize($_POST['brand_id'],"brand");
		if($type)
			$db->Execute(
				sprintf("
					UPDATE
						shop_brands
					SET
						imagetype=%s
					WHERE
						id=%u
				"
					,$db->Quote($type)
					,$_POST['brand_id']
				)
			);
	}
	
	if($_FILES['content_image']['error'] === UPLOAD_ERR_OK)
	{
		$type=pathinfo($_FILES['content_image']['name'], PATHINFO_EXTENSION);
		if(@move_uploaded_file($_FILES['content_image']['tmp_name'], $config['path'].'images/brand/content/'.($_POST['brand_id']+0).'.'.$type))
			$db->Execute(
				sprintf("
					UPDATE
						shop_brands
					SET
						content_imagetype=%s
					WHERE
						id=%u
				"
					,$db->Quote($type)
					,$_POST['brand_id']
				)
			);
	}
	
	$ok=$db->CompleteTrans();
	if(!$ok)
    	error("There was a problem whilst updating the brand, please try again.  If this persists please notify your designated support contact","Database Error");
?>