<?
	if($_POST['country_id']>1)
	{
		$db->SetTransactionMode("SERIALIZABLE");
		$db->StartTrans();
		
		$db->Execute(
			sprintf("
				DELETE FROM
					shop_countries
				WHERE
					id=%u
			"
				,$_POST['country_id']
			)
		);
		//Update orphaned suppliers
		$db->Execute(
			sprintf("
				UPDATE
					shop_suppliers
				SET
					country_id=1
				WHERE
					country_id=%u
			"
				,$_POST['country_id']
			)
		);
		
		$ok=$db->CompleteTrans();
		if(!$ok)
			error("There was a problem whilst removing the country, please try again.  If this persists please notify your designated support contact","Database Error");
	}
	else
		error("You cannot delete this country", "Stop");
?>