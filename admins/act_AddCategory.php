<?
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

	$max=$db->Execute(
		sprintf("
			SELECT
				MAX(ord) AS max
			FROM
				shop_categories
			WHERE
				parent_id=%u
		"
			,$_POST['parent_id']
		)
	);

	if($max->fields["max"]=="")
		$ord=0;
	else
		$ord=$max->fields["max"]+1;

	//Create fields variable
	if(count($_POST['shopfield']['name'])>0)
	{
		foreach($_POST['shopfield']['name'] as $field)
			$fields.=$field."\n";
	}

	$db->Execute(
		sprintf("
			INSERT INTO
				shop_categories
			SET
				parent_id = %u
				,name = %s
				,vars = %s
				,meta_title = %s
				,meta_description = %s
				,meta_keywords = %s
				,discount = %f
				,discount_trigger = %u
				,content = %s
				,ord = %u
				,childord = %u
				,productord = %s
				,listing_type=%s
				,color = %s
				,custom_search = %u
				,buy_3_cheapest_free = %u
				,home_slider = %u
				,exclude_discounts = %u
				,content_visible = %u
				,hidden = %u
				,slider_title = %s
				,slider_description = %s
				,link_category_id = %u
		"
			,$_POST['parent_id']
			,$db->Quote($_POST['name'])
			,$db->Quote(trim($fields))
			,$db->Quote($_POST['meta_title'])
			,$db->Quote($_POST['meta_description'])
			,$db->Quote($_POST['meta_keywords'])
			,$_POST['discount']
			,$_POST['discount_trigger']
			,$db->Quote($_POST['content'][0])
			,$ord
			,$_POST['childord']
			,$db->Quote($_POST['productord'])
			,$db->Quote($_POST['listing_type'])
			,$db->Quote($_POST['color'])
			,($_POST['custom_search']=="on") ? 1 : 0
			,$_POST['buy_3_cheapest_free']
			,$_POST['home_slider']
			,$_POST['exclude_discounts']
			,$_POST['content_visible']
			,$_POST['hidden']
			,$db->Quote($_POST['slider_title'])
			,$db->Quote($_POST['slider_description'])
			,$_POST['link_category_id']
		)
	);
	$category_id=$db->Insert_ID();

	$tree=new DBTree($db,"shop_categories");
	$tree->addPage($category_id, $_POST['parent_id']);
	
	if($_FILES['image']['error'] == UPLOAD_ERR_OK)
	{
		$type=$resize->resize($category_id,"category");
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
					,$category_id
				)
			);
	}
    if($_FILES['box_image']['error'] == UPLOAD_ERR_OK)
	{
        $info = pathinfo($_FILES['box_image']['name']);
		if(move_uploaded_file($_FILES['box_image']['tmp_name'], $config['path'].'images/category/box_'.$category_id.'.'.$info['extension']))
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
					,$category_id
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
					if(!move_uploaded_file($_FILES['slider_image']['tmp_name'], $config['path'].'images/category/slider/'.$category_id.'.'.$img_type))
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
								,$category_id
							)
						);
				}
			}
		}
	}

	//Insert restrictions
	if(count($_POST['area'])>0)
	{
		$query="INSERT INTO shop_category_restrictions (category_id,area_id) VALUES ";
		$vars=array();
		$count=0;
		foreach($_POST['area'] as $area_id)
		{
			if($count>0)
				$query.=",";
			$query.="(%u,%u)";
			$vars[]=$category_id;
			$vars[]=$area_id;
			$count++;
		}
		$db->Execute(vsprintf($query,$vars));
	}
	
	$category_id=$_POST['parent_id'];
	
	$ok=$db->CompleteTrans();
	if(!$ok)
    	error("There was a problem whilst adding the category, please try again.  If this persists please notify your designated support contact","Database Error");
	else
	{
		if(count($alert))
		{
			$ok = false;
			alert("The category has been inserted. However:<br />".implode("<br />\n", $alert), "Warning");
			alertRender();
		}
		$_SESSION['alert'] = 'Category inserted.';
	}
?>
