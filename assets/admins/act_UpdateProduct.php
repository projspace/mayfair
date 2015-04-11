<?
	$alert = array();
	if(file_exists("../mapdir/sitemap.txt"))
		unlink("../mapdir/sitemap.txt");
		
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();

	$shopspec=fix_specs($_POST['shopspec']);
	
	if($_POST['soldout']=="on")
		$soldout=1;
	else
		$soldout=0;

	$db->Execute(
		$sql=sprintf("
			UPDATE
				shop_products
			SET
				brand_id=%u
				,hidden=%u
				,name=%s
				,code=%s
				,price=%f
				,price_old=%f
				,discount=%f
				,weight=%f
				,width=%f
				,height=%f
				,`length`=%f
				,packing=%f
				,shipping=%f
				,gift=%u
				,vat=%u
				,vat_exempt=%u
				,buy_1_get_1_free=%u
				,recent_productions=%u
				,home_slider=%u
				,360_view=%u
				,product_search=%u
				,description=%s
				,short_description=%s
				,options=%s
				,specs=%s
				,soldout=%u
				,custom=%s
				,meta_title=%s
				,meta_description=%s
				,meta_keywords=%s
				,slider_title = %s
				,slider_description = %s
				,low_stock_trigger=%u
				,hide_stock_trigger=%u
				,stock=%u
				,no_shipping=%u
				,flat_rate_shipping=%u
				,pick_up_only=%u
				,facebook=%u
				,twitter=%u
				,google=%u
				,pinterest=%u
				,special=%u
				,reviews=%u
				,alt_size=%u
				,exclude_discounts=%u
				,featured=%u
				,hide_quick_view=%u
				,hide_add_cart=%u
				,hide_more_details=%u
				,hide_price=%u
				,label=%s
				,zoom=%s
				,gender=%s
				,age=%s
				,warehouse=%s
				,added=%s
				,`updated` = NOW()
			WHERE
				id=%u
			OR
			(
				parent_id=%u
			AND
				parent_id > 0
			)
		"
			,$_POST['brand_id']
			,$_POST['hidden']
			,$db->Quote($_POST['name'])
			,$db->Quote($_POST['code'])
			,$_POST['price']
			,$_POST['price_old']
			,$_POST['discount']
			,$_POST['weight']
			,$_POST['width']
			,$_POST['height']
			,$_POST['length']
			,$_POST['packing']
			,$_POST['shipping']
			,$_POST['gift']
			,$_POST['vat']
			,$_POST['vat_exempt']
			,$_POST['buy_1_get_1_free']
			,$_POST['recent_productions']
			,$_POST['home_slider']
			,$_POST['360_view']
			,$_POST['product_search']
			,$db->Quote($_POST['content'][0])
			,$db->Quote($_POST['content'][1])
			,$db->Quote(serialize(array_stripslashes(reduce_options($_POST['shopopt']))))
			,$db->Quote(serialize(array_stripslashes($shopspec)))
			,$soldout
			,$db->Quote(serialize(array_stripslashes($_POST['custom'])))
			,$db->Quote($_POST['meta_title'])
			,$db->Quote($_POST['meta_description'])
			,$db->Quote($_POST['meta_keywords'])
			,$db->Quote($_POST['slider_title'])
			,$db->Quote($_POST['slider_description'])
			,$_POST['low_stock_trigger']
			,$_POST['hide_stock_trigger']
			,$_POST['stock']
			,$_POST['no_shipping']
			,$_POST['flat_rate_shipping']
			,$_POST['pick_up_only']
			,$_POST['facebook']
			,$_POST['twitter']
			,$_POST['google']
			,$_POST['pinterest']
			,$_POST['special']
			,$_POST['reviews']
			,$_POST['alt_size']
			,$_POST['exclude_discounts']
			,$_POST['featured']
            ,$_POST['hide_quick_view']
			,$_POST['hide_add_cart']
			,$_POST['hide_more_details']
			,$_POST['hide_price']
			,$db->Quote($_POST['label'])
			,$db->Quote($_POST['zoom'])
			,$db->Quote($_POST['gender'])
			,$db->Quote($_POST['age'])
			,$db->Quote($_POST['warehouse'])
			,$db->Quote(implode('-', array_reverse(explode('/', $_POST['added']))))
			,$_POST['product_id']
			,$_POST['product_id']
		)
	);
	
	$db->Execute(
		$sql=sprintf("
			UPDATE
				shop_products
			SET
				guid=%s
			WHERE
				id=%u
		"
			,$db->Quote($_POST['guid'])
			,$_POST['product_id']
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
					shop_products
				WHERE
					id=%u
			"
				,$_POST['product_id']
			)
		);

		$row=$get->FetchRow();
		if($row['imagetype']!="")
		{
			unlink("../images/product/".$row['id'].".".$row['imagetype']);
			unlink("../images/product/thumbs/".$row['id'].".".$row['imagetype']);
		}
		$db->Execute(
			sprintf("
				UPDATE
					shop_products
				SET
					imagetype=''
				WHERE
					id=%u
				OR
					parent_id=%u
			"
				,$_POST['product_id']
				,$_POST['product_id']
			)
		);
	}
	
	if($_POST['pdf_delete']=="on")
	{
		@unlink($config['path']."downloads/product/pdf/".$_POST['product_id'].".pdf");
		
		$db->Execute(
			sprintf("
				UPDATE
					shop_products
				SET
					pdf = 0
				WHERE
					id=%u
				OR
					parent_id=%u
			"
				,$_POST['product_id']
				,$_POST['product_id']
			)
		);
	}
	
	if($_FILES['pdf']['error'] == UPLOAD_ERR_OK)
	{
		if(move_uploaded_file($_FILES['pdf']['tmp_name'], $config['path']."downloads/product/pdf/".$_POST['product_id'].".pdf"))
		{
			$db->Execute(
				sprintf("
					UPDATE
						shop_products
					SET
						pdf=1
					WHERE
						id=%u
					OR
						parent_id=%u

				"
					,$_POST['product_id']
					,$_POST['product_id']
				)
			);
		}
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
					if(!move_uploaded_file($_FILES['slider_image']['tmp_name'], $config['path'].'images/product/slider/'.$_POST['product_id'].'.'.$img_type))
						$alert[] = "There was a problem whilst saving the slider image. Please try again.";
					else
						$db->Execute(
							sprintf("
								UPDATE
									shop_products
								SET
									slider_image_type=%s
								WHERE
									id = %u

							"
								,$db->Quote($img_type)
								,$_POST['product_id']
							)
						);
				}
			}
		}
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
			,$db->Quote($_POST['audio_file_id'])
		)
	);
	$upload = $upload->FetchRow();
	if($upload)
	{
		$path_info = pathinfo($upload['name']);
		$audio_type = false;
		switch(strtolower(trim($path_info['extension'])))
		{
			case 'mp3':
				$audio_type = "mp3";
				break;
			case 'aac':
				$audio_type = "aac";
				break;
			case 'm4a':
				$audio_type = "m4a";
				break;
			default:
				$alert[] = "The audio file is not a valid format. Allowed audio formats: mp3, aac and m4a";
				break;
		}
		if($audio_type)
		{
			if(!rename($config['path'].'downloads/temp/'.$upload['guid'], $config['path'].'downloads/product/audio/'.$_POST['product_id'].'.'.$audio_type))
				$alert[] = "There was a problem whilst saving the audio file. Please try again.";
			else
				$db->Execute(
					sprintf("
						UPDATE
							shop_products
						SET
							audio_type=%s
						WHERE
							id = %u

					"
						,$db->Quote($audio_type)
						,$_POST['product_id']
					)
				);
		}
	}
	elseif($_POST['audio_delete']+0)
	{
		$filename = $config['path'].'downloads/product/audio/'.$product['id'].'.'.$product['audio_type'];
		if(file_exists($filename))
			@unlink($filename);
		$db->Execute(
			sprintf("
				UPDATE
					shop_products
				SET
					audio_type=''
				WHERE
					id = %u

			"
				,$product['id']
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
			case 'flv':
				$video_type = "flv";
				break;
			case 'mp4':
				$video_type = "mp4";
				break;
			default:
				$alert[] = "The video file is not a valid format. Allowed video formats: flv and mp4";
				break;
		}
		if($video_type)
		{
			if(!rename($config['path'].'downloads/temp/'.$upload['guid'], $config['path'].'downloads/product/video/'.$_POST['product_id'].'.'.$video_type))
				$alert[] = "There was a problem whilst saving the video file. Please try again.";
			else
				$db->Execute(
					sprintf("
						UPDATE
							shop_products
						SET
							video_type=%s
						WHERE
							id = %u

					"
						,$db->Quote($video_type)
						,$_POST['product_id']
					)
				);
		}
	}
	elseif($_POST['video_delete']+0)
	{
		$filename = $config['path'].'downloads/product/video/'.$product['id'].'.'.$product['video_type'];
		if(file_exists($filename))
			@unlink($filename);
		$db->Execute(
			sprintf("
				UPDATE
					shop_products
				SET
					video_type=''
				WHERE
					id = %u

			"
				,$product['id']
			)
		);
	}

	//Searchable vars
	$count=0;
	while($row=$product_vars->FetchRow())
	{
		$found=false;
		foreach($shopspec as $spec)
		{
			if($row['name']==$spec['name'])
			{
				$db->Execute(
					sprintf("
						UPDATE
							shop_product_vars
						SET
							value=%s
							,ord=%u
						WHERE
							id=%u
					"
						,$db->Quote($spec['value'])
						,$count
						,$row['id']
					)
				);
				$count++;
				$found=true;
			}
		}
		if(!$found)
		{
			$db->Execute(
				sprintf("
					DELETE FROM
						shop_product_vars
					WHERE
						id=%u
				"
					,$row['id']
				)
			);
		}
	}

	reset($shopspec);
	foreach($shopspec as $spec)
	{
		$product_vars->MoveFirst();
		$found=false;
		while($row=$product_vars->FetchRow())
		{
			if($row['name']==$spec['name'])
				$found=true;
		}
		if(!$found)
		{
			$db->Execute(
				sprintf("
					INSERT INTO
						shop_product_vars (
							product_id
							,name
							,value
							,ord
						) VALUES (
							%u
							,%s
							,%s
							,%u
						)
				"
					,$_POST['product_id']
					,$db->Quote($spec['name'])
					,$db->Quote($spec['value'])
					,$count
				)
			);
			$count++;
		}
	}

	//Take care of updating restrictions (beware, complex method below!)
	$query="INSERT INTO shop_product_restrictions (product_id,area_id) VALUES ";
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
						$vars[]=$_POST['product_id'];
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
						shop_product_restrictions
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
		
	if(is_array($_REQUEST['saved_tags']) && count($_REQUEST['saved_tags']))
		$db->Execute(
			sprintf("
				DELETE FROM
					shop_product_tags
				WHERE
					product_id=%u
				AND
					tag_id NOT IN (%s)
			"
				,$_POST['product_id']
				,implode(', ', $_REQUEST['saved_tags'])
			)
		);
	else
		$db->Execute(
			sprintf("
				DELETE FROM
					shop_product_tags
				WHERE
					product_id=%u
			"
				,$_POST['product_id']
			)
		);
	if(is_array($_REQUEST['tags']) && count($_REQUEST['tags']))
	{
		$format = array();
		$args = array();
		
		foreach($_REQUEST['tags'] as $tag)
		{
			$tag = strtolower(trim($tag));
			if($tag == '')
				continue;
				
			$meta_tag = $db->Execute(
				sprintf("
					SELECT
						*
					FROM
						shop_meta_tags
					WHERE
						name LIKE %s
					LIMIT 1
				"
				,$db->Quote($tag)
				)
			);
			if(!$meta_tag->RecordCount())
			{
				$db->Execute(
					sprintf("
						INSERT INTO
							shop_meta_tags
						SET
							name = %s
					"
					,$db->Quote($tag)
					)
				);
				$tag_id=$db->Insert_ID();
			}
			else
			{
				$meta_tag = $meta_tag->FetchRow();
				$tag_id = $meta_tag['id'];
			}
				
			$format[] = '(%u, %u)';
			$args[] = $_POST['product_id'];
			$args[] = $tag_id;
		}
		
		if(count($format))
		{
			$format = "INSERT INTO shop_product_tags (product_id, tag_id) VALUES ".implode(',', $format);
			$db->Execute(vsprintf($format, $args));
		}
	}
	
	$filters = array();
	foreach((array)$_POST['filter_ids'] as $filter_id)
		if($filter_id+0)
			$filters[] = sprintf("(%u, %u)", $_POST['product_id'], $filter_id);
	
	$db->Execute(
		sprintf("
			DELETE FROM
				shop_product_filters
			WHERE
				product_id=%u
		"
			,$_POST['product_id']
		)
	);
	
	if(count($filters))
		$db->Execute(
			sprintf("
				INSERT INTO
					shop_product_filters
				(
					product_id
					,filter_id
				)
				VALUES
					%s
			"
				,implode(',',$filters)
			)
		);
		
	//warnings
	if(is_array($_REQUEST['saved_trigger']) && count($_REQUEST['saved_trigger']))
	{
		$db->Execute(
			sprintf("
				DELETE FROM
					shop_product_warnings
				WHERE
					product_id=%u
				AND
					`trigger` NOT IN (%s)
			"
				,$_POST['product_id']
				,implode(', ', $_REQUEST['saved_trigger'])
			)
		);
		
		foreach($_REQUEST['saved_trigger'] as $key=>$trigger)
			$db->Execute(
				sprintf("
					UPDATE
						shop_product_warnings
					SET
						message = %s
					WHERE
						product_id=%u
					AND
						`trigger`=%d
				"
					,$db->Quote($_REQUEST['saved_message'][$key])
					,$_POST['product_id']
					,$trigger
				)
			);
	}
	else
		$db->Execute(
			sprintf("
				DELETE FROM
					shop_product_warnings
				WHERE
					product_id=%u
			"
				,$_POST['product_id']
			)
		);
		
	if(is_array($_REQUEST['trigger']) && count($_REQUEST['trigger']))
	{
		$format = array();
		$args = array();
		
		foreach($_REQUEST['trigger'] as $key=>$trigger)
		{
			$format[] = '(%u, %d, %s)';
			$args[] = $_POST['product_id'];
			$args[] = $trigger;
			$args[] = $db->Quote($_REQUEST['message'][$key]);
		}
		
		if(count($format))
		{
			$format = "INSERT INTO shop_product_warnings (product_id, `trigger`, message) VALUES ".implode(',', $format);
			$db->Execute(vsprintf($format, $args));
		}
	}
	
	//options
	if(is_array($_REQUEST['saved_ids']) && count($_REQUEST['saved_ids']))
	{
		$db->Execute(
			sprintf("
				DELETE FROM
					shop_product_options
				WHERE
					product_id=%u
				AND
					id NOT IN (%s)
			"
				,$_POST['product_id']
				,implode(', ', $_REQUEST['saved_ids'])
			)
		);
		
		foreach($_REQUEST['saved_ids'] as $key=>$option_id)
			$db->Execute(
				sprintf("
					UPDATE
						shop_product_options
					SET
						upc_code = %s
						,ean_code = %s
						,size_id = %s
						,width_id = %s
						,color_id = %s
						,quantity = %u
						,price = %f
					WHERE
						product_id=%u
					AND
						id=%u
				"
					,trim($_REQUEST['saved_upc_code'][$key])?$db->Quote($_REQUEST['saved_upc_code'][$key]):'NULL'
					,$db->Quote($_REQUEST['saved_ean_code'][$key])
					,($_REQUEST['saved_size_id'][$key]+0)?$_REQUEST['saved_size_id'][$key]+0:'NULL'
					,($_REQUEST['saved_width_id'][$key]+0)?$_REQUEST['saved_width_id'][$key]+0:'NULL'
					,($_REQUEST['saved_color_id'][$key]+0)?$_REQUEST['saved_color_id'][$key]+0:'NULL'
					,$_REQUEST['saved_quantity'][$key]
					,$_REQUEST['saved_price_differential'][$key]
					,$_POST['product_id']
					,$option_id
				)
			);
	}
	else
		$db->Execute(
			sprintf("
				DELETE FROM
					shop_product_options
				WHERE
					product_id=%u
			"
				,$_POST['product_id']
			)
		);
		
	if(is_array($_REQUEST['upc_code']) && count($_REQUEST['upc_code']))
	{
		$format = array();
		$args = array();
		
		foreach($_REQUEST['upc_code'] as $key=>$upc_code)
		{
			$format[] = '(%u, %s, %s, %s, %s, %s, %u, %f)';
			$args[] = $_POST['product_id'];
			$args[] = trim($upc_code)?$db->Quote($upc_code):'NULL';
			$args[] = $db->Quote($_REQUEST['ean_code'][$key]);
			$args[] = ($_REQUEST['size_id'][$key]+0)?$_REQUEST['size_id'][$key]+0:'NULL';
			$args[] = ($_REQUEST['width_id'][$key]+0)?$_REQUEST['width_id'][$key]+0:'NULL';
			$args[] = ($_REQUEST['color_id'][$key]+0)?$_REQUEST['color_id'][$key]+0:'NULL';
			$args[] = $_REQUEST['quantity'][$key];
			$args[] = $_REQUEST['price_differential'][$key];
		}
		
		if(count($format))
		{
			$format = "INSERT INTO shop_product_options (product_id, `upc_code`, `ean_code`, size_id, width_id, color_id, quantity, price) VALUES ".implode(',', $format);
			$db->Execute(vsprintf($format, $args));
		}
	}
	
	//fitting guides
	if(count($_POST['column_ids']))
	{
		$db->Execute(
			sprintf("
				DELETE FROM
					shop_product_fitting_guide_columns
				WHERE
					product_id=%u
			"
				,$_POST['product_id']
			)
		);
		
		$sql = array();
		foreach($_POST['column_ids'] as $column_id)
			$sql[] = sprintf("(%u, %u)", $_POST['product_id'], $column_id);
		if(count($sql))
			$db->Execute("INSERT INTO shop_product_fitting_guide_columns (product_id, column_id) VALUES ".implode(',', $sql));
	}
	
	$ok=$db->CompleteTrans();
	if(!$ok)
    	error("There was a problem whilst updating the product, please try again.  If this persists please notify your designated support contact","Database Error");
	else
	{
		$search=new Search($config);
		$search->update("product",$_POST['product_id']+0,$_POST['name'],strip_tags($_POST['content'][0]),array('code'=>$_POST['code'], 'description'=>strip_tags($_POST['content'][1])));
		
		$sitemap = new Sitemap($config, $db);
		$sitemap->load();
		$sitemap->update();
		$sitemap->save();
		
		if($_FILES['image']['error'] == UPLOAD_ERR_OK)
		{
			$tmp_file = $config['path'].'images/tmp/product_image_'.$_REQUEST['product_id'];
			if(file_exists($tmp_file))
				unlink($tmp_file);
				
			if(move_uploaded_file($_FILES['image']['tmp_name'], $tmp_file))
			{
				$item['id'] = $_REQUEST['product_id'];
				
				$img_info = getimagesize($tmp_file);
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
						$alert[] = "The uploaded file is not a valid image. Allowed image formats: jpg, png and gif";
						$img_type = false;
						break;
				}
				if($img_type)
				{
					$data = array();
					
					foreach($config['size']['product'] as $type=>$size)
					{
						if($type == 'original')
						{
							@copy($tmp_file, $config['path'].'images/product/'.$type.'/product_'.$_REQUEST['product_id'].'.'.$img_type);
							continue;
						}
						if($type != 'image')
							$dest_file = $config['path'].'images/product/'.$type.'/product_'.$_REQUEST['product_id'].'.'.$img_type;
						else
							$dest_file = $config['path'].'images/product/product_'.$_REQUEST['product_id'].'.'.$img_type;
							
						$item['image']['src_file'] = $tmp_file;
						$item['image']['dest_file'] = $dest_file;
						$item['image']['width'] = $size['x'];
						$item['image']['height'] = $size['y'];
						$item['image']['description'] = $size['description'];
						
						$data['images'][] = $item;
					}
					
					$data['screen_width'] = $_POST['screen_width']+0;
				
					$data['redirect_url'] = $config['dir']."index.php?fuseaction=admin.editProduct&act=update_image&product_id=".$_REQUEST['product_id']."&category_id=".$_REQUEST['category_id'];
					$data['redirect_info'] = true;
					
					$data['cancel_url'] = $config['dir']."index.php?fuseaction=admin.products&category_id=".$_REQUEST['category_id'];
					
					header("location: ".$config['dir']."index.php?fuseaction=admin.cropImages&data=".urlencode(serialize($data)));
					exit;
				}
			}
		}
		
		if(count($alert))
		{
			$ok = false;
			alert("The product has been updated. However:<br />".implode("<br />\n", $alert), "Warning");
			alertRender();
		}
		
		$_SESSION['alert'] = 'Product updated.';
	}
?>
