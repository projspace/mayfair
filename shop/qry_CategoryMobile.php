<?
	$fields = array();
	$fields['category_id'] = $_REQUEST['category_id']+0;
	if(isset($_REQUEST['filters']))
		foreach($_REQUEST['filters'] as $key=>$row)
		{
			$fields['filters['.$key.'][name]'] = $row['name'];
			foreach((array)$row['value'] as $filter_item_id)
				$fields['filters['.$key.'][value][]'][] = $filter_item_id;
		}
	
	if(isset($_REQUEST['sortby']))
		$fields['sortby'] = $_REQUEST['sortby'];
	if(isset($_REQUEST['pageview']))
		$fields['pageview'] = $_REQUEST['pageview'];
	if(isset($_REQUEST['page']))
		$fields['page'] = $_REQUEST['page'];
	if(isset($_REQUEST['type']))
		$fields['category_type'] = $_REQUEST['type'];
		
	$post = array();
	foreach($fields as $key=>$value)
		if(is_array($value))
		{
			foreach($value as $sub_value)
				$post[] = urlencode($key).'='.urlencode($sub_value);
		}
		else
			$post[] = urlencode($key)."=".urlencode($value);
		
	//var_dump($post);
		
	$ch = curl_init();
	
	// set URL and other appropriate options
	curl_setopt($ch, CURLOPT_URL, $config['dir'].'ajax/qry_Category.php');
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, implode('&', $post));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	// grab URL and pass it to the browser
	$ajax = curl_exec($ch);
	//var_dump($ajax);exit;
	$ajax = json_decode($ajax, true);

	// close cURL resource, and free up system resources
	curl_close($ch);
	
	//var_dump($post, $ajax);exit;
?>
