<?
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();
	
	$db->Execute(
		sprintf("
			UPDATE
				shop_countries
			SET
				area_id=%u
				,name=%s
				,price=%f
				,minimal_price=%f
				,`default`=%u
			WHERE
				id=%u
		"
			,$_POST['area_id']
			,$db->Quote($_POST['name'])
			,$_POST['price']
			,$_POST['minimal_price']
			,$_POST['default']
			,$_POST['country_id']
		)
	);
	
	if($_POST['default']+0)
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
				,$_POST['country_id']
			)
		);
	}
	
	$ok=$db->CompleteTrans();
	if(!$ok)
    	error("There was a problem whilst updating the country, please try again.  If this persists please notify your designated support contact","Database Error");
?>