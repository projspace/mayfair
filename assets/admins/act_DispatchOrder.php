<?
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();
	
	$db->Execute(
		sprintf("
			UPDATE
				shop_orders
			SET
				shipping_number=%s
				,dispatched=%u
			WHERE
				id=%u
		"
			,$db->Quote($_POST['shipping_number'])
            ,time()
			,$_POST['order_id']
		)
	);
	
	$ok=$db->CompleteTrans();
	if(!$ok)
    	error("There was a problem whilst dispatching the order, please try again.  If this persists please notify your designated support contact","Database Error");
	else
	{
		$vars = array();
		$vars['products']=$products->GetRows();
		$vars['order']=$order;		
		$vars['shipping_number']=$_POST['shipping_number'];
		$sent=$mail->sendMessage($vars,"OrderDispatched",$order['email'],$order['name']);
		if(!$sent)
		{
			error("There was a problem whilst sending the dispatch email, please try again.  If this persists please notify your designated support contact","Database Error");
			$ok = false;
		}
	}
?>