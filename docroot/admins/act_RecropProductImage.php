<?
	$data = array();
	$type = safe($_REQUEST['type']);
	$size = $config['size']['product'][$type];
	
	if($type != 'image')
		$dest_file = $config['path'].'images/product/'.$type.'/'.$image['id'].'.'.$image['imagetype'];
	else
		$dest_file = $config['path'].'images/product/'.$image['id'].'.'.$image['imagetype'];
			
	$item['image']['src_file'] = $config['path'].'images/product/original/'.$image['id'].'.'.$image['imagetype'];
	$item['image']['dest_file'] = $dest_file;
	$item['image']['width'] = $size['x'];
	$item['image']['height'] = $size['y'];
	if(isset($size['min_x']))
		$item['image']['min_width'] = $size['min_x'];
	if(isset($size['min_y']))
		$item['image']['min_height'] = $size['min_y'];
	$item['image']['description'] = $size['description'];
	
	$data['images'][] = $item;
	
	if(count($data['images']))
	{
		$data['screen_width'] = 950;
			
		$data['redirect_url'] = $config['dir']."index.php?fuseaction=admin.products&category_id=".$_REQUEST['category_id'];
		$data['redirect_info'] = false;
		
		$data['cancel_url'] = $config['dir']."index.php?fuseaction=admin.editProduct&act=reuploadImage&image_id=".$image['id']."&product_id=".$_REQUEST['product_id']."&category_id=".$_REQUEST['category_id'];
		
		header("location: ".$config['dir']."index.php?fuseaction=admin.cropImages&data=".urlencode(serialize($data)));
		exit;
	}
	else
		error("There was a problem setting up the image. Please try again later.");
?>