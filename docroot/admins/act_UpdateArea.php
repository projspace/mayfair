<?
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();
	
	$db->Execute(
		sprintf("
			UPDATE
				shop_areas
			SET
				name = %s
				,over_weight_unit = %u
				,over_price = %f
				,free_shipping = %f
			WHERE
				id=%u
		"
			,$db->Quote($_POST['name'])
			,$_POST['over_weight_unit']
			,$_POST['over_price']
			,$_POST['free_shipping']
			,$_POST['area_id']
		)
	);
	
	if(is_array($_REQUEST['saved_weight']) && count($_REQUEST['saved_weight']))
	{
		$db->Execute(
			sprintf("
				DELETE FROM
					shop_area_prices
				WHERE
					area_id=%u
				AND
					weight NOT IN (%s)
			"
				,$_POST['area_id']
				,implode(', ', $_REQUEST['saved_weight'])
			)
		);
		
		foreach($_REQUEST['saved_weight'] as $key=>$weight)
			$db->Execute(
				sprintf("
					UPDATE
						shop_area_prices
					SET
						price = %f
					WHERE
						area_id=%u
					AND
						weight=%u
				"
					,$_REQUEST['saved_price'][$key]
					,$_POST['area_id']
					,$weight
				)
			);
	}
	else
		$db->Execute(
			sprintf("
				DELETE FROM
					shop_area_prices
				WHERE
					area_id=%u
			"
				,$_POST['area_id']
			)
		);
		
	if(is_array($_REQUEST['weight']) && count($_REQUEST['weight']))
	{
		$format = array();
		$args = array();
		
		foreach($_REQUEST['weight'] as $key=>$weight)
		{
			$format[] = '(%u, %u, %f)';
			$args[] = $_POST['area_id'];
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
    	error("There was a problem whilst updating the area, please try again.  If this persists please notify your designated support contact","Database Error");
?>