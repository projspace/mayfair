<?
	if(!count($orders))
	{
		error("Please select at least one order.","Error");
		return;
	}
	
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();
	
	$db->Execute(
		sprintf("
			UPDATE
				shop_orders
			SET
				dispatched=%u
			WHERE
				id IN (%s)
		"
			,time()
			,implode(',', array_keys($orders))
		)
	);
	
	$ok=$db->CompleteTrans();
	if(!$ok)
    	error("There was a problem whilst dispatching the order, please try again.  If this persists please notify your designated support contact","Database Error");
	else
	{
		foreach($orders as $order_id=>$order)
		{
			$vars = array();
			$vars['products']=$order['products'];
			$vars['order']=$order;		
			$sent=$mail->sendMessage($vars,"OrderDispatched",$order['email'],$order['name']);
			if(!$sent)
			{
				error("There was a problem whilst sending the dispatch email, please try again.  If this persists please notify your designated support contact","Database Error");
				$ok = false;
			}
		}
	}
?>