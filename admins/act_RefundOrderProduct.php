<?
	if(!$product || !$order || !$txnvars)
	{
		$ok = false;
		return;
	}
	
	$params['reference']=$txnvars['txn_id'];
	$params['amount']=$product['price']*$product['quantity'];
	$ok = $psp->Refund($params);
	if($ok)
	{
		$db->Execute(
			sprintf("
				UPDATE
					shop_order_products
				SET
					refunded = 1
				WHERE
					id = %u
			"
				,$product['id']
			)
		);
		$db->Execute(
			sprintf("
				UPDATE
					shop_orders
				SET
					refunded = refunded + %f
				WHERE
					id = %u
			"
				,$product['price']*$product['quantity']
				,$order['id']
			)
		);
	}
?>
