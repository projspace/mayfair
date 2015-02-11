<?
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();
	
	$db->Execute(
		sprintf("
			INSERT INTO
				shop_countries
			SET
				area_id = %u
				,name = %s
				,price=%f
				,minimal_price=%f
				,`default`=%u
		"
			,$_POST['area_id']
			,$db->Quote($_POST['name'])
			,$_POST['price']
			,$_POST['minimal_price']
			,$_POST['default']
		)
	);
	$country_id=$db->Insert_ID();
	
	if($country_id && $_POST['default']+0)
	{
		$db->Execute(
			sprintf("
				UPDATE
					shop_countries
				SET
					`default` = 0
				WHERE
					id != %u
			"
				,$country_id
			)
		);
	}
	
	$ok=$db->CompleteTrans();
	if(!$ok)
    	error("There was a problem whilst adding the country, please try again.  If this persists please notify your designated support contact","Database Error");
?>