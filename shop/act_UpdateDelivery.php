<?
	//echo "<pre>".print_r($_REQUEST,true)."</pre>";
	$cart=array();
	foreach($delivery as $address)
	{
		$area=$db->Execute(
			sprintf("
				SELECT
					shop_areas.name
				FROM
					shop_areas
					,shop_countries
				WHERE
					shop_countries.area_id=shop_areas.id
				AND
					shop_countries.name=%s
			"
				,$db->Quote($address['country'])
			)
		);
		$address['area']=$area->fields['name'];
		$cart[$address['cart_id']]['delivery'][]=$address;
	}
		
	$keys=array_keys($cart);
	foreach($keys as $key)
	{
		echo $key."<br />";
		echo "<pre>".print_r($cart[$key],true)."</pre>";	
		$db->Execute(
			sprintf("
				UPDATE
					shop_session_cart
				SET
					custom=%s
				WHERE
					id=%u
				AND
					session_id=%s
			"
				,$db->Quote(serialize($cart[$key]))
				,$key
				,$db->Quote($session->session_id)
			)
		);
	}
?>