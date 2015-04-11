<?
    if(!isset($image_entity))
	    $image_entity = 'product';

    $image_ratios = array();
    foreach($config['size'][$image_entity] as $key=>$size)
        if($key != 'original')
        {
            if($size['y']+0)
				$ratio = ((float)($size['x'] / $size['y'])).'';
			else
				$ratio = 0;

            if(isset($image_ratios[$ratio]))
				$image_ratios[$ratio][] = $key;
			else
				$image_ratios[$ratio] = array($key);
        }

    $image_sizes = array();
    foreach($image_ratios as $ratio=>$keys)
		if(count($keys) > 1)
		{
			$max_area = 0;
			$max_key = 0;
			foreach($keys as $key)
			{
				$area = $config['size'][$image_entity][$key]['x'] * $config['size'][$image_entity][$key]['y'];
				if($area >= $max_area)
				{
					$max_area = $area;
					$max_key = $key;
				}
			}
			if(!$max_key)
				continue;

            $image_sizes[$max_key] = $config['size'][$image_entity][$max_key];
            $image_sizes[$max_key]['sub_images'] = array();
			foreach($keys as $key)
				if($key != $max_key)
					$image_sizes[$max_key]['sub_images'][$key] = $config['size'][$image_entity][$key];
		}
?>