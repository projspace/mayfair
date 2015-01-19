<?
	ini_set('memory_limit','128M');
	clearstatcache();
	$data = unserialize(stripslashes($_REQUEST['data']));
	if(!$data)
	{
		$ok = false;
		error("Invalid image data. Please try again.");
		return;
	}
	
	$image_ratios = array();
	foreach((array)$data['images'] as $key=>$item)
	{
		if(isset($item['image']))
		{
			if($item['image']['height']+0)
				$ratio = ((float)($item['image']['width'] / $item['image']['height'])).'';
			else
				$ratio = 0;
				
			if(isset($image_ratios[$ratio]))
				$image_ratios[$ratio][] = $key;
			else
				$image_ratios[$ratio] = array($key);
			
			$img_info = getimagesize($item['image']['src_file']);
			if(!$img_info)
			{
				$ok = false;
				error("The main file is not a valid image. Allowed image formats: jpg, png and gif");
				return;
			}
			if($img_info[0] > $data['screen_width']+0)
			{
				try 
				{
					$vimage = new $vcfg['vimage']['cls']($item['image']['src_file']);
					$vimage->resize($data['screen_width']+0, 0)->save($item['image']['src_file']);
				}
				catch (Exception $e) 
				{
					$ok = false;
					error("There was a problem resizing the image. Please try again later.");
					return;
				}
			}
		}
		
		if(isset($item['thumbnail']))
		{
			$img_info = getimagesize($item['thumbnail']['src_file']);
			if(!$img_info)
			{
				$ok = false;
				error("The thumbnail file is not a valid image. Allowed image formats: jpg, png and gif");
				return;
			}
			
			if($img_info[0] > $data['screen_width']+0)
			{
				try 
				{
					$vimage = new $vcfg['vimage']['cls']($item['thumbnail']['src_file']);
					$vimage->resize($data['screen_width']+0, 0)->save($item['thumbnail']['src_file']);
				}
				catch (Exception $e) 
				{
					$ok = false;
					error("There was a problem resizing the thumbnail. Please try again later.");
					return;
				}
			}
		}
	}

	foreach($image_ratios as $ratio=>$keys)
		if(count($keys) > 1)
		{
			$max_area = 0;
			$max_key = 0;
			foreach($keys as $key)
			{
				$area = $data['images'][$key]['image']['width'] * $data['images'][$key]['image']['height'];
				if($area >= $max_area)
				{
					$max_area = $area;
					$max_key = $key;
				}
			}
			if(!$max_key)
				continue;
			
			$data['images'][$max_key]['image']['sub_images'] = array();
			foreach($keys as $key)
				if($key != $max_key)
				{
					$data['images'][$max_key]['image']['sub_images'][] = $data['images'][$key];
					unset($data['images'][$key]);
				}
		}

	$ok = true;
?>