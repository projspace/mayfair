<?
	if($_POST['supplier_id']>1)
	{
		$db->SetTransactionMode("SERIALIZABLE");
		$db->StartTrans();
		
		$db->Execute(
			sprintf("
				DELETE FROM
					shop_suppliers
				WHERE
					id=%u
			"
				,$_POST['supplier_id']
			)
		);
		//Update orphaned brands to use default supplier
		$db->Execute(
			sprintf("
				UPDATE
					shop_brands
				SET
					supplier_id=1
				WHERE
					supplier_id=%u
			"
				,$_POST['supplier_id']
			)
		);
		
		$ok=$db->CompleteTrans();
		if(!$ok)
			error("There was a problem whilst removing the supplier, please try again.  If this persists please notify your designated support contact","Database Error");
	}
	else
		error("You cannot delete this supplier", "Stop");
?>