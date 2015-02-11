<?
	$params = array();
	$params['transaction_id']=$txnvars['txn_id'];
	if($order['captured']+0) //refund
	{
		$params['account_number']=$txnvars['account_number'];//$order['paid'];
		$params['amount'] = $order['paid'];
		$ok = $psp->Refund($params);
	}
	else // remove from settlement
		$ok = $psp->Cancel($params);
		
	if($ok)
	{
		$db->Execute(
			$sql = sprintf("
				UPDATE
					shop_orders
				SET
					refunded = %f
					,refund_admin_id = %u
					,refund_date = NOW()
				WHERE
					id = %u
			"
				,$order['paid']
				,$session->account_id
				,$order['id']
			)
		);
	}
?>
