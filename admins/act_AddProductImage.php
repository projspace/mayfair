<?
	$alert = array();
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();
	
	$data = array();
	$keys=array_keys($_FILES['image']['tmp_name']);
	foreach($keys as $uploadid)
	{
		$add=$db->Execute(
			sprintf("
				INSERT INTO
					shop_product_images
				SET
					product_id = %u
			"
				,$_POST['product_id']
			)
		);
		$imageid=$db->Insert_ID();
		
		if($imageid)
		{
			$tmp_file = $config['path'].'images/tmp/product_image_'.$imageid;
			if(file_exists($tmp_file))
				unlink($tmp_file);
			
			if(move_uploaded_file($_FILES['image']['tmp_name'][$uploadid], $tmp_file))
			{
                $item = array();
				$item['id'] = $imageid;
				
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
				if(!$img_type)
					continue;
				
				foreach($config['size']['product'] as $type=>$size)
				{
					if($type == 'original')
					{
						@copy($tmp_file, $config['path'].'images/product/'.$type.'/'.$imageid.'.'.$img_type);
						continue;
					}
					if($type != 'image')
						$dest_file = $config['path'].'images/product/'.$type.'/'.$imageid.'.'.$img_type;
					else
						$dest_file = $config['path'].'images/product/'.$imageid.'.'.$img_type;
						
					$item['image']['src_file'] = $tmp_file;
					$item['image']['dest_file'] = $dest_file;
					$item['image']['width'] = $size['x'];
					$item['image']['height'] = $size['y'];
					if(isset($size['min_x']))
						$item['image']['min_width'] = $size['min_x'];
                    else
                        unset($item['image']['min_width']);
					if(isset($size['min_y']))
						$item['image']['min_height'] = $size['min_y'];
                    else
                        unset($item['image']['min_height']);
					$item['image']['description'] = $size['description'];
					
					$data['images'][] = $item;
				}
			}
/*
			$type=multiple_resize($_FILES['image']['tmp_name'][$uploadid], "image".$imageid, "product");
			if($type)
			{
				$db->Execute(
					sprintf("
						UPDATE
							shop_product_images
						SET
							imagetype=%s
						WHERE id=%u
					"
						,$db->Quote($type)
						,$imageid
					)
				);
			}
			else
				$db->Execute(
					sprintf("
						DELETE FROM
							shop_product_images
						WHERE
							id=%u
					"
						,$imageid
					)
				);
*/
		}
	}
	
	$keys=array_keys($_FILES['360_view']['tmp_name']);
	foreach($keys as $uploadid)
	{
		$db->Execute(
			sprintf("
				INSERT INTO
					shop_product_360_images
				SET
					product_id = %u
			"
				,$_POST['product_id']
			)
		);
		$imageid=$db->Insert_ID();
		
		if($imageid)
		{
			$pathinfo = pathinfo($_FILES['360_view']['name'][$uploadid]);
			if(move_uploaded_file($_FILES['360_view']['tmp_name'][$uploadid], $config['path'].'images/product/360_view/'.$imageid.'.'.$pathinfo['extension']))
			{
				$db->Execute(
					sprintf("
						UPDATE
							shop_product_360_images
						SET
							image_type=%s
						WHERE id=%u
					"
						,$db->Quote($pathinfo['extension'])
						,$imageid
					)
				);
			}
			else
				$db->Execute(
					sprintf("
						DELETE FROM
							shop_product_360_images
						WHERE
							id=%u
					"
						,$imageid
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
			,$db->Quote($_POST['zip_file_id'])
		)
	);
	$upload = $upload->FetchRow();
	if($upload)
	{
		$path_info = pathinfo($upload['name']);
		$type = false;
		switch(strtolower(trim($path_info['extension'])))
		{
			case 'zip':
				$type = "zip";
				break;
			default:
				$alert[] = "The file is not a zip archive. Allowed formats: zip";
				break;
		}
		if($type)
		{
			$zip_file = $config['path'].'downloads/temp/'.$upload['guid'];
			$zip_dir = $zip_file."-images";
			$ret = exec("unzip -o ".$zip_file." -d ".$zip_dir, $output, $return);
			if($return === 0)
			{
				$path = $zip_dir.'/';
				$dir=new DirectoryIterator($path);
				$files=array();
				
				foreach($dir as $item)
				{
					$count++;
					if(!$item->isDot())
					{
						if(!$item->isDir())
						{
							$file=$item->getFileInfo();
							$name = trim($file->getFilename());
							$filename = $path.$name;
							$img_info = getimagesize($filename);
							if(!$img_info)
								continue;
								
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
								continue;
							
							if($img_info[0] > 600 || $img_info[1] > 600)
								continue;
								
							$ret = preg_match('/(\d+)/i', $name, $matches);
							if(!$ret)
								continue;
								
							$index = $matches[1]+0;
								
							$files[$index] = array('path'=>$filename, 'type'=>$img_type);
						}
					}
				}
				ksort($files, SORT_NUMERIC);
				foreach($files as $file)
				{
					$db->Execute(
						sprintf("
							INSERT INTO
								shop_product_360_images
							SET
								product_id = %u
						"
							,$_POST['product_id']
						)
					);
					$imageid=$db->Insert_ID();
					if($imageid)
					{
						if(@rename($file['path'], $config['path'].'images/product/360_view/'.$imageid.'.'.$file['type']))
						{
							$db->Execute(
								sprintf("
									UPDATE
										shop_product_360_images
									SET
										image_type=%s
									WHERE id=%u
								"
									,$db->Quote($file['type'])
									,$imageid
								)
							);
						}
						else
							$db->Execute(
								sprintf("
									DELETE FROM
										shop_product_360_images
									WHERE
										id=%u
								"
									,$imageid
								)
							);
					}
				}
			}
			else
				$alert[] = "The zip file could not be extracted.";
			@unlink($zip_file);
			if(file_exists($zip_dir))
				delete_structure($zip_dir.'/', true);
		}
	}
	
	$ok=$db->CompleteTrans();
	if(!$ok)
    	error("There was a problem whilst adding the image, please try again.  If this persists please notify your designated support contact","Database Error");
	else
	{
		if(count($alert))
		{
			$ok = false;
			alert("Some of the product images have been inserted. However:<br />".implode("<br />\n", $alert), "Warning");
			alertRender();
		}
		elseif(count($data['images']))
		{
			$data['screen_width'] = $_POST['screen_width']+0;
				
			$data['redirect_url'] = $config['dir']."index.php?fuseaction=admin.editProduct&act=update_images&product_id=".$_REQUEST['product_id']."&category_id=".$_REQUEST['category_id'];
			$data['redirect_info'] = true;
			
			$data['cancel_url'] = $config['dir']."index.php?fuseaction=admin.products&category_id=".$_REQUEST['category_id'];
			
			header("location: ".$config['dir']."index.php?fuseaction=admin.cropImages&data=".urlencode(serialize($data)));
			exit;
		}
	}
?>