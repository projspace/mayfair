<?
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();
	
	$db->Execute(
		sprintf("
			INSERT INTO
				shop_areas
			SET
				name = %s
				,over_weight_unit = %u
				,over_price = %f
				,free_shipping = %f
		"
			,$db->Quote($_POST['name'])
			,$_POST['over_weight_unit']
			,$_POST['over_price']
			,$_POST['free_shipping']
		)
	);
	$area_id=$db->Insert_ID();
	
	if(is_array($_REQUEST['weight']) && count($_REQUEST['weight']))
	{
		$format = array();
		$args = array();
		
		foreach($_REQUEST['weight'] as $key=>$weight)
		{
			$format[] = '(%u, %u, %f)';
			$args[] = $area_id;
			$args[] = $weight;
			$args[] = $_REQUEST['price'][$key];
		}
		
		if(count($format))
		{
			$format = "INSERT INTO shop_area_prices (area_id, weight, price) VALUES ".implode(',', $format);
			$db->Execute(vsprintf($format, $args));
		}
	}
	
	$ok=$db->CompleteTrans();
	if(!$ok)
    	error("There was a problem whilst adding the area, please try again.  If this persists please notify your designated support contact","Database Error");
?>