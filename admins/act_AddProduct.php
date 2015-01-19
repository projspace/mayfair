<?
	$alert = array();
	if(file_exists("../mapdir/sitemap.txt"))
		unlink("../mapdir/sitemap.txt");
		
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();
		
	if($_POST['soldout']=="on")
		$soldout=1;
	else
		$soldout=0;

	$shopspec=fix_specs($_POST['shopspec']);

	//Get max order
	$max=$db->Execute(
		sprintf("
			SELECT
				MAX(ord) AS max
			FROM
				shop_products
			WHERE
				category_id=%u
		"
			,$_POST['category_id']
		)
	);

	$db->Execute(
		sprintf("
			INSERT INTO
				shop_products
			SET
				category_id = %u
				,brand_id = %u
				,parent_id = %u
				,hidden = %u
				,name = %s
				,guid = %s
				,code = %s
				,price = %f
				,price_old = %f
				,discount = %f
				,weight = %f
				,width=%f
				,height=%f
				,`length`=%f
				,packing = %f
				,shipping = %f
				,gift = %u
				,vat = %u
				,vat_exempt = %u
				,buy_1_get_1_free = %u
				,recent_productions = %u
				,home_slider = %u
				,360_view = %u
				,product_search = %u
				,description = %s
				,short_description = %s
				,stock = %u
				,`trigger` = %u
				,options = %s
				,specs = %s
				,soldout = %u
				,custom = %s
				,meta_title = %s
				,meta_description = %s
				,meta_keywords = %s
				,slider_title = %s
				,slider_description = %s
				,low_stock_trigger=%u
				,hide_stock_trigger=%u
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
				,ord = %u
				,label=%s
				,zoom=%s
				,gender=%s
				,age=%s
				,warehouse=%s
				,added=%s
				,`inserted` = NOW()
				,`updated` = NOW()
		"
			,$_POST['category_id']
			,$_POST['brand_id']
			,0
			,$_POST['hidden']
			,$db->Quote($_POST['name'])
			,$db->Quote($_POST['guid'])
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
			,$_POST['stock']
			,$_POST['trigger']
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
			,$max->fields['max']+1
			,$db->Quote($_POST['label'])
			,$db->Quote($_POST['zoom'])
			,$db->Quote($_POST['gender'])
			,$db->Quote($_POST['age'])
			,$db->Quote($_POST['warehouse'])
			,$db->Quote(implode('-', array_reverse(explode('/', $_POST['added']))))
		)
	);

	$product_id=$db->Insert_ID();

	if($product_id)
	{
		/*
		if($_FILES['image']['error'] == UPLOAD_ERR_OK)
		{
			$type=multiple_resize($_FILES['image']['tmp_name'], $product_id, "product");
			if($type)
				$db->Execute(
					sprintf("
						UPDATE
							shop_products
						SET
							imagetype=%s
						WHERE
							id=%u
					"
						,$db->Quote($type)
						,$product_id
					)
				);
		}
		*/
		if($_FILES['pdf']['error'] == UPLOAD_ERR_OK)
		{
			if(move_uploaded_file($_FILES['pdf']['tmp_name'], $config['path']."downloads/product/pdf/".$product_id.".pdf"))
				$db->Execute(
					sprintf("
						UPDATE
							shop_products
						SET
							pdf=1
						WHERE
							id=%u
					"
						,$product_id
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
						if(!move_uploaded_file($_FILES['slider_image']['tmp_name'], $config['path'].'images/product/slider/'.$product_id.'.'.$img_type))
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
									,$product_id
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
				if(!rename($config['path'].'downloads/temp/'.$upload['guid'], $config['path'].'downloads/product/audio/'.$product_id.'.'.$audio_type))
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
							,$product_id
						)
					);
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
				if(!rename($config['path'].'downloads/temp/'.$upload['guid'], $config['path'].'downloads/product/video/'.$product_id.'.'.$video_type))
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
							,$product_id
						)
					);
			}
		}
		
		if(count($shopspec)>0)
		{
			$query="INSERT INTO shop_product_vars (product_id,name,value,ord) VALUES ";
			$vars=array();
			for($i=0;$i<count($shopspec);$i++)
			{
				if($i>0)
					$query.=",";
				$query.="(%u,%s,%s,%u)";
				$vars[]=$product_id;
				$vars[]=$db->Quote($shopspec[$i]['name']);
				$vars[]=$db->Quote($shopspec[$i]['value']);
				$vars[]=$i;
			}
			$db->Execute(vsprintf($query,$vars));
		}

		//Insert restrictions
		if(count($_POST['area'])>0)
		{
			$query="INSERT INTO shop_product_restrictions (product_id,area_id) VALUES ";
			$vars=array();
			$count=0;
			foreach($_POST['area'] as $area_id)
			{
				if($count>0)
					$query.=",";
				$query.="(%u,%u)";
				$vars[]=$product_id;
				$vars[]=$area_id;
				$count++;
			}
			$db->Execute(vsprintf($query,$vars));
		}
		
		if(is_array($_REQUEST['tags']) && count($_REQUEST['tags']))
		{
			$format = array();
			$args = array();
			
			foreach($_REQUEST['tags'] as $tag)
			{
				$tag = strtolower(trim($tag));
				if($tag == '')
					continue;
					
				$article_tag = $db->Execute(
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
				if(!$article_tag->RecordCount())
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
					$article_tag = $article_tag->FetchRow();
					$tag_id = $article_tag['id'];
				}
					
				$format[] = '(%u, %u)';
				$args[] = $product_id;
				$args[] = $tag_id;
			}
			
			if(count($format))
			{
				$format = "INSERT INTO shop_product_tags (product_id, tag_id) VALUES ".implode(',', $format);
				$db->Execute(vsprintf($format, $args));
			}
		}
		
		$filters = array();
		foreach($_POST['filter_ids'] as $filter_id)
			if($filter_id+0)
				$filters[] = sprintf("(%u, %u)", $product_id, $filter_id);
				
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
		
		if(is_array($_REQUEST['trigger']) && count($_REQUEST['trigger']))
		{
			$format = array();
			$args = array();
			
			foreach($_REQUEST['trigger'] as $key=>$trigger)
			{
				$format[] = '(%u, %d, %s)';
				$args[] = $product_id;
				$args[] = $trigger;
				$args[] = $db->Quote($_REQUEST['message'][$key]);
			}
			
			if(count($format))
			{
				$format = "INSERT INTO shop_product_warnings (product_id, `trigger`, message) VALUES ".implode(',', $format);
				$db->Execute(vsprintf($format, $args));
			}
		}
		
		if(is_array($_REQUEST['upc_code']) && count($_REQUEST['upc_code']))
		{
			$format = array();
			$args = array();
			
			foreach($_REQUEST['upc_code'] as $key=>$upc_code)
			{
				$format[] = '(%u, %s, %s, %s, %s, %s, %u, %f)';
				$args[] = $product_id;
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
			$sql = array();
			foreach($_POST['column_ids'] as $column_id)
				$sql[] = sprintf("(%u, %u)", $product_id, $column_id);
			if(count($sql))
				$db->Execute("INSERT INTO shop_product_fitting_guide_columns (product_id, column_id) VALUES ".implode(',', $sql));
		}
	}
	
	$ok=$db->CompleteTrans();
	if(!$ok)
    	error("There was a problem whilst adding the product, please try again.  If this persists please notify your designated support contact","Database Error");
	else
	{
		$search=new Search($config);
		$search->add("product",$product_id,$_POST['name'],strip_tags($_POST['content'][0]),array('code'=>$_POST['code'], 'description'=>strip_tags($_POST['content'][1])));
		
		$sitemap = new Sitemap($config, $db);
		$sitemap->load();
		$sitemap->update();
		$sitemap->save();
		
		if($_FILES['image']['error'] == UPLOAD_ERR_OK)
		{
			$tmp_file = $config['path'].'images/tmp/product_image_'.$product_id;
			if(file_exists($tmp_file))
				unlink($tmp_file);
				
			if(move_uploaded_file($_FILES['image']['tmp_name'], $tmp_file))
			{
				$item['id'] = $product_id;
				
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
							@copy($tmp_file, $config['path'].'images/product/'.$type.'/product_'.$product_id.'.'.$img_type);
							continue;
						}
						if($type != 'image')
							$dest_file = $config['path'].'images/product/'.$type.'/product_'.$product_id.'.'.$img_type;
						else
							$dest_file = $config['path'].'images/product/product_'.$product_id.'.'.$img_type;
							
						$item['image']['src_file'] = $tmp_file;
						$item['image']['dest_file'] = $dest_file;
						$item['image']['width'] = $size['x'];
						$item['image']['height'] = $size['y'];
						
						$data['images'][] = $item;
					}
					
					$data['screen_width'] = $_POST['screen_width']+0;
				
					$data['redirect_url'] = $config['dir']."index.php?fuseaction=admin.editProduct&act=update_image&product_id=".$product_id."&category_id=".$_REQUEST['category_id'];
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
			alert("The product has been inserted. However:<br />".implode("<br />\n", $alert), "Warning");
			alertRender();
		}
		$_SESSION['alert'] = 'Product inserted.';
	}
?>
