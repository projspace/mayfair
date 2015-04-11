<?
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();
	
	for($i=0;$i<$_POST['count'];$i++)
	{
		$db->Execute(
			sprintf("
				UPDATE 
					shop_products 
				SET 
					stock = %u
					,`trigger` = %u
				WHERE 
					id = %u
			"
				,$_POST["stock$i"]
				,$_POST["trigger$i"]
				,$_POST["product$i"]
			)
		);
	}
	
	$ok=$db->CompleteTrans();
	if(!$ok)
    	error("There was a problem whilst updating the stock, please try again.  If this persists please notify your designated support contact","Database Error");
?>