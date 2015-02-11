<?
	$data = unserialize(urldecode($_POST['data']));
	if(!$data)
	{
		$ok = false;
		error("Invalid image data. Please try again.");
		return;
	}
	
	$redirect_info = array();
	$unlink_queue = array();
	foreach((array)$data['images'] as $key=>$item)
	{
		$info = array();
		$info['id'] = $item['id'];
		
		if(isset($item['image']))
		{
			try 
			{
				$vimage = new $vcfg['vimage']['cls']($item['image']['src_file']);
				$vimage->crop($_POST['image'][$key]['width'], $_POST['image'][$key]['height'], $_POST['image'][$key]['x'], $_POST['image'][$key]['y'])->resize($item['image']['width'], $item['image']['height'])->save($item['image']['dest_file']);
				
				if(is_array($item['image']['sub_images']) && count($item['image']['sub_images']))
					foreach($item['image']['sub_images'] as $sub_item)
					{
						$vimage = new $vcfg['vimage']['cls']($item['image']['src_file']);
						$vimage->crop($_POST['image'][$key]['width'], $_POST['image'][$key]['height'], $_POST['image'][$key]['x'], $_POST['image'][$key]['y'])->resize($sub_item['image']['width'], $sub_item['image']['height'])->save($sub_item['image']['dest_file']);
					}
				
				$info['image_info'] = getimagesize($item['image']['dest_file']);
			} 
			catch (Exception $e) 
			{
				$ok = false;
				error("There was a problem whilst cropping/resizing the image, please try again.  If this problem persists please contact your designated support contact.","Database Error");
				return;
			}
		}
		
		if(isset($item['thumbnail']))
		{
			try 
			{
				$vimage = new $vcfg['vimage']['cls']($item['thumbnail']['src_file']);
				$vimage->crop($_POST['thumbnail'][$key]['width'], $_POST['thumbnail'][$key]['height'], $_POST['thumbnail'][$key]['x'], $_POST['thumbnail'][$key]['y'])->resize($item['thumbnail']['width'], $item['thumbnail']['height'])->save($item['thumbnail']['dest_file']);
				
				$info['thumbnail_info'] = getimagesize($item['thumbnail']['dest_file']);
			} 
			catch (Exception $e) 
			{
				$ok = false;
				error("There was a problem whilst cropping/resizing the thumbnail, please try again.  If this problem persists please contact your designated support contact!","Database Error");
				return;
			}
		}
		
		if(isset($item['image']))
			$unlink_queue = $item['image']['src_file'];
			
		if(isset($item['thumbnail']))
			$unlink_queue = $item['thumbnail']['src_file'];
			
		$redirect_info[] = $info;
	}	
	
	foreach($unlink_queue as $file)
		@unlink($file);
		
	$ok = true;
	$redirect_url = array();
	$redirect_url[] = $data['redirect_url'];
	if($data['redirect_info'])
		$redirect_url[] = 'redirect_info='.urlencode(serialize($redirect_info));
	
	header("location: ".implode('&', $redirect_url));
	exit;
?>