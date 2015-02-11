<?
	if(isset($_FILES['landing_image']) && $_FILES['landing_image']['error'] !== UPLOAD_ERR_OK && $_FILES['landing_image']['error'] !== 4)
	{
		$error = '';
		switch($_FILES['landing_image']['error'])
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
	
	if($_FILES['landing_image']['error'] === UPLOAD_ERR_OK)
	{
		$img_info = getimagesize($_FILES['landing_image']['tmp_name']);
		if(!$img_info)
		{
			error("The file is not a valid image. Allowed image formats: jpg, png and gif","Upload Error");
			return;
		}
		
		if($img_info[0] != 740 || $img_info[1] != 493)
		{
			error("The image must be 740 x 493.","Upload Error");
			return;
		}
		
		$landing_img_type = false;
		switch($img_info[2])
		{
			case IMAGETYPE_JPEG:
				$landing_img_type = "jpg";
				break;
			case IMAGETYPE_GIF:
				$landing_img_type = "gif";
				break;
			case IMAGETYPE_PNG:
				$landing_img_type = "png";
				break;
		}
		if(!$landing_img_type)
		{
			error("The file is not a valid image. Allowed image formats: jpg, png and gif","Upload Error");
			return;
		}
	}

    if($_FILES['image']['error'] === UPLOAD_ERR_OK)
	{
		$img_info = getimagesize($_FILES['image']['tmp_name']);
		if(!$img_info)
		{
			error("The box file is not a valid image. Allowed image formats: jpg, png and gif","Upload Error");
			return;
		}

		if($img_info[0] != 373)
        {
            error("The image must have with 373px.","Upload Error");
            return;
        }
	}

    if($_FILES['box_image']['error'] === UPLOAD_ERR_OK)
	{
		$img_info = getimagesize($_FILES['box_image']['tmp_name']);
		if(!$img_info)
		{
			error("The box file is not a valid image. Allowed image formats: jpg, png and gif","Upload Error");
			return;
		}

		if($img_info[0] != 281 || $img_info[1] != 281)
		{
			error("The image must be 281 x 281.","Upload Error");
			return;
		}
	}
	
	$alert = array();
	if(file_exists("../mapdir/sitemap.txt"))
		unlink("../mapdir/sitemap.txt");

	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();
		
	//Create fields variable
	if(count($_POST['shopfield']['name'])>0)
	{
		foreach($_POST['shopfield']['name'] as $field)
			$fields.=$field."\n";
	}
	
	if($_POST['link_category_id']+0)
		$db->Execute(
			sprintf("
				UPDATE
					shop_categories
				SET
					name=%s
					,link_category_id = %u
					,hidden = %u
				WHERE
					id=%u
			"
				,$db->Quote($_POST['name'])
				,$_POST['link_category_id']
				,$_POST['hidden']
				,$_POST['category_id']
			)
		);
	else
		$db->Execute(
			sprintf("
				UPDATE
					shop_categories
				SET
					name=%s
					,vars=%s
					,content=%s
					,delivery=%s
					,childord=%u
					,productord=%s
					,listing_type=%s
					,meta_title=%s
					,meta_description=%s
					,meta_keywords=%s
					,discount=%f
					,discount_trigger=%u
					,color=%s
					,custom_search=%u
					,buy_3_cheapest_free = %u
					,fitting_guide=%u
					,slider_title = %s
					,slider_description = %s
					,fitting_pdf_visible = %u
					,hidden_new_products = %u
					,hidden_clearance = %u
					,exclude_discounts = %u
					,content_visible = %u
					,hidden = %u
					,no_landing_page = %u
					,google_category_id = %s
					,link_category_id = 0
				WHERE
					id=%u
			"
				,$db->Quote($_POST['name'])
				,$db->Quote(trim($fields))
				,$db->Quote($_POST['content'][0])
				,$db->Quote($_POST['content'][1])
				,$_POST['childord']
				,$db->Quote($_POST['productord'])
				,$db->Quote($_POST['listing_type'])
				,$db->Quote($_POST['meta_title'])
				,$db->Quote($_POST['meta_description'])
				,$db->Quote($_POST['meta_keywords'])
				,$_POST['discount']
				,$_POST['discount_trigger']
				,$db->Quote($_POST['color'])
				,($_POST['custom_search']=="on") ? 1 : 0
				,$_POST['buy_3_cheapest_free']
				,$_POST['fitting_guide']
				,$db->Quote($_POST['slider_title'])
				,$db->Quote($_POST['slider_description'])
				,$_POST['fitting_pdf_visible']
				,$_POST['hidden_new_products']
				,$_POST['hidden_clearance']
				,$_POST['exclude_discounts']
				,$_POST['content_visible']
				,$_POST['hidden']
				,$_POST['no_landing_page']
				,($_POST['google_category_id']+0)?$_POST['google_category_id']+0:'NULL'
				,$_POST['category_id']
			)
		);
	echo $db->ErrorMsg();
	
	if($_POST['delete']=="on")
	{
		$get=$db->Execute(
			sprintf("
				SELECT
					id
					,imagetype
				FROM
					shop_categories
				WHERE
					id=%u
			"
				,$_POST['category_id']
			)
		);

		$row=$get->FetchRow();
		if($row['imagetype']!="")
		{
			unlink("../images/category/".$row['id'].".".$row['imagetype']);
			unlink("../images/category/thumbs/".$row['id'].".".$row['imagetype']);
		}

		$db->Execute(
			sprintf("
				UPDATE
					shop_categories
				SET
					imagetype=''
				WHERE
					id=%u
			"
				,$_POST['category_id']
			)
		);
	}
	
	if($_FILES['image']['error'] == UPLOAD_ERR_OK)
	{
		$type=$resize->resize($_POST['category_id'],"category");
		if($type)
			$db->Execute(
				sprintf("
					UPDATE
						shop_categories
					SET
						imagetype=%s
					WHERE
						id=%u
				"
					,$db->Quote($type)
					,$_POST['category_id']
				)
			);
	}

    if($_POST['box_delete']=="on")
	{
		$get=$db->Execute(
			sprintf("
				SELECT
					id
					,box_imagetype
				FROM
					shop_categories
				WHERE
					id=%u
			"
				,$_POST['category_id']
			)
		);

		$row=$get->FetchRow();
		if($row['box_imagetype']!="")
			unlink("../images/category/box_".$row['id'].".".$row['box_imagetype']);

		$db->Execute(
			sprintf("
				UPDATE
					shop_categories
				SET
					box_imagetype=''
				WHERE
					id=%u
			"
				,$_POST['category_id']
			)
		);
	}

	if($_FILES['box_image']['error'] == UPLOAD_ERR_OK)
	{
		$info = pathinfo($_FILES['box_image']['name']);
		if(move_uploaded_file($_FILES['box_image']['tmp_name'], $config['path'].'images/category/box_'.$_POST['category_id'].'.'.$info['extension']))
            $db->Execute(
				sprintf("
					UPDATE
						shop_categories
					SET
						box_imagetype=%s
					WHERE
						id=%u
				"
					,$db->Quote($info['extension'])
					,$_POST['category_id']
				)
			);
	}
	
	if($_FILES['slider_image']['error'] === UPLOAD_ERR_OK)
	{
		$img_info = getimagesize($_FILES['slider_image']['tmp_name']);
		if(!$img_info)
		{
			$alert[] = "The slider file is not a valid image. Allowed image formats: jpg, png and gif";
		}
		else
		{
			if($img_info[0] != 400 || $img_info[1] != 288)
				$alert[] = "The slider image must be 400x288";
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
						$alert[] = "The slider file is not a valid image. Allowed image formats: jpg, png and gif";
						break;
				}
				if($img_type)
				{
					if(!move_uploaded_file($_FILES['slider_image']['tmp_name'], $config['path'].'images/category/slider/'.$_POST['category_id'].'.'.$img_type))
						$alert[] = "There was a problem whilst saving the slider image. Please try again.";
					else
						$db->Execute(
							sprintf("
								UPDATE
									shop_categories
								SET
									slider_image_type=%s
								WHERE
									id = %u

							"
								,$db->Quote($img_type)
								,$_POST['category_id']
							)
						);
				}
			}
		}
	}
	
	if($_FILES['landing_image']['error'] === UPLOAD_ERR_OK)
	{
		if(file_exists($filename = $config['path'].'images/category/landing_'.$_POST['category_id'].'.'.$landing_img_type))
			@unlink($filename);
			
		if(!@move_uploaded_file($_FILES['landing_image']['tmp_name'], $config['path'].'images/category/landing_'.$_POST['category_id'].'.'.$landing_img_type))
		{
			error("There was a problem whilst saving the image. Please try again.","Upload Error");
			return;
		}
		
		$db->Execute(
			sprintf("
				UPDATE
					shop_categories
				SET
					landing_image_type = %s
				WHERE
					id = %u
			"
				,$db->Quote($landing_img_type)
				,$_POST['category_id']
			)
		);
	}
	
	if($_POST['delete_landing_video'])
	{
		if(file_exists($filename = $config['path'].'video/category/landing_'.$_POST['category_id'].'.mp4'))
			@unlink($filename);
			
		$db->Execute(
			sprintf("
				UPDATE
					shop_categories
				SET
					landing_video_type = ''
				WHERE
					id = %u
			"
				,$_POST['category_id']
			)
		);
	}
	
	$upload = $db->Execute(
		$sql = sprintf("
			SELECT
				*
			FROM
				temporary_uploads
			WHERE
				guid = %s
		"
			,$db->Quote($_POST['video_file_id'])
		)
	);
	$upload = $upload->FetchRow();
	if($upload)
	{
		$path_info = pathinfo($upload['name']);
		$video_type = false;
		switch(strtolower(trim($path_info['extension'])))
		{
			case 'mp4':
				$video_type = "mp4";
				break;
			default:
				$alert[] = "The video file is not a valid format. Allowed video formats: mp4";
				break;
		}
		if($video_type)
		{
			if(!rename($config['path'].'downloads/temp/'.$upload['guid'], $config['path'].'downloads/category/landing_'.$_POST['category_id'].'.'.$video_type))
				$alert[] = "There was a problem whilst saving the video file. Please try again.";
			else
				$db->Execute(
					sprintf("
						UPDATE
							shop_categories
						SET
							landing_video_type=%s
						WHERE
							id = %u

					"
						,$db->Quote($video_type)
						,$_POST['category_id']
					)
				);
		}
	}
		
	if($_POST['fitting_pdf_delete']=="on")
	{
		@unlink($config['path']."downloads/fitting_pdf/".$_POST['category_id'].".pdf");
		
		$db->Execute(
			sprintf("
				UPDATE
					shop_categories
				SET
					fitting_pdf = 0
				WHERE
					id=%u
			"
				,$_POST['category_id']
			)
		);
	}
	
	if($_FILES['fitting_pdf']['error'] == UPLOAD_ERR_OK)
	{
		if(move_uploaded_file($_FILES['fitting_pdf']['tmp_name'], $config['path']."downloads/fitting_pdf/".$_POST['category_id'].".pdf"))
		{
			$db->Execute(
				sprintf("
					UPDATE
						shop_categories
					SET
						fitting_pdf=1
					WHERE
						id=%u
				"
					,$_POST['category_id']
				)
			);
		}
	}

	//Take care of updating restrictions (beware, complex method below!)
	$query="INSERT INTO shop_category_restrictions (category_id,area_id) VALUES ";
	$count=0;
	$vars=array();
	while($row=$areas->FetchRow())
	{
		$found=false;
		if(count($_POST['area'])>0)
		{
			foreach($_POST['area'] as $area_id)
			{
				if($row['id']==$area_id)
				{
					if($row['restriction_id']!="")
					{
						//No need to do anything, already in there
						$found=true;
					}
					else
					{
						//Add
						$found=true;
						if($count>0)
							$query.=",";
						$query.="(%u,%u)";
						$vars[]=$_POST['category_id'];
						$vars[]=$row['id'];
						$count++;
					}
				}
			}
		}
		if(!$found && $row['restriction_id']!="")
		{
			//Remove
			$db->Execute(
				sprintf("
					DELETE FROM
						shop_category_restrictions
					WHERE
						id=%u
				"
					,$row['restriction_id']
				)
			);
		}
	}
	if($count>0)
		$db->Execute(vsprintf($query,$vars));
	echo $db->ErrorMsg();
	
	//fitting guides
	if(count($_POST['guide_ids']))
	{
		$db->Execute(
			sprintf("
				DELETE FROM
					shop_category_fitting_guides
				WHERE
					category_id=%u
			"
				,$_POST['category_id']
			)
		);
		
		$sql = array();
		foreach($_POST['guide_ids'] as $guide_id)
			$sql[] = sprintf("(%u, %u)", $_POST['category_id'], $guide_id);
		if(count($sql))
			$db->Execute("INSERT INTO shop_category_fitting_guides (category_id, guide_id) VALUES ".implode(',', $sql));
	}
	
	if(count($_POST['column_ids']))
	{
		$db->Execute(
			sprintf("
				DELETE FROM
					shop_category_fitting_guide_columns
				WHERE
					category_id=%u
			"
				,$_POST['category_id']
			)
		);
		
		$sql = array();
		foreach($_POST['column_ids'] as $column_id)
			$sql[] = sprintf("(%u, %u)", $_POST['category_id'], $column_id);
		if(count($sql))
			$db->Execute("INSERT INTO shop_category_fitting_guide_columns (category_id, column_id) VALUES ".implode(',', $sql));
	}
	
	$category_id=$_POST['parent_id'];
	
	$ok=$db->CompleteTrans();
	if(!$ok)
    	error("There was a problem whilst updating the category, please try again.  If this persists please notify your designated support contact","Database Error");
	else
	{
		if(count($alert))
		{
			$ok = false;
			alert("The category has been updated. However:<br />".implode("<br />\n", $alert), "Warning");
			alertRender();
		}
		$_SESSION['alert'] = 'Category updated.';
	}
?>
