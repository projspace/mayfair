<?
	if($_POST['area_id'] > 1)
	{
		$db->SetTransactionMode("SERIALIZABLE");
		$db->StartTrans();
		
		$db->Execute(
			sprintf("
				DELETE FROM
					shop_areas
				WHERE
					id=%u
			"
				,$_POST['area_id']
			)
		);
		
		//Update orphaned countries to use default area
		$db->Execute(
			sprintf("
				UPDATE
					shop_countries
				SET
					area_id=1
				WHERE
					area_id=%u
			"
				,$_POST['area_id']
			)
		);
		
		$ok=$db->CompleteTrans();
		if(!$ok)
			error("There was a problem whilst removing the area, please try again.  If this persists please notify your designated support contact","Database Error");
	}
	else
		error("You cannot delete this area", "Stop");
?>