<?
	$sql_filter = array();
	
	$sql_filter[] = sprintf("shop_orders.processed = 0");

	if(is_array($_POST['order_ids']) && count($_POST['order_ids']))
		$sql_filter[] = sprintf("shop_orders.id IN (%s)", implode(',', array_map(create_function('$a', 'return $a+0;'), $_POST['order_ids'])));
	else
		$sql_filter[] = sprintf("0");
	
	if(count($sql_filter))
		$sql_filter = implode(' AND ', $sql_filter);
	else
		$sql_filter = '0';
		
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();
	
	$db->Execute(
		sprintf("
			UPDATE
				shop_orders
			SET
				processed=%u
			WHERE
				%s
		"
			,time()
			,$sql_filter
		)
	);
	
	$ok=$db->CompleteTrans();
	if(!$ok)
    	error("There was a problem whilst processing the orders, please try again.  If this persists please notify your designated support contact","Database Error");
?>