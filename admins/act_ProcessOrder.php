<?
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();
	
	$db->Execute(
		sprintf("
			UPDATE
				shop_orders
			SET
				processed=%u
			WHERE
				id=%u
		"
			,time()
			,$_POST['order_id']
		)
	);

    $params = array(
        'vars' => array(
		'total' => $order['total']
        ,'shipping' => $order['shipping']
        ,'packing' => $order['packing']
        ,'tax' => $order['tax']
        ,'promotional_discount' => $order['promotional_discount']
        )
    );
	
    $params['transaction_id'] = $txnvars['txn_id'];

    if(!$psp->captureTransaction($params, $error_reason))
    {
        error("There was a problem whilst capturing the total amount, please try again. ".var_export($error_reason, true),"Database Error");
        return;
    }
	
	$ok=$db->CompleteTrans();
	if(!$ok)
    	error("There was a problem whilst processing the order, please try again.  If this persists please notify your designated support contact","Database Error");
?>