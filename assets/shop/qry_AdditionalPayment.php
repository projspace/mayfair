<?
	$additional_payment=$db->Execute(
		sprintf("
			SELECT
				shop_user_accounts.additional_payment_label label
				,sot1.value reference
				,sot2.value cvs
			FROM
			(
				shop_user_accounts
				,shop_orders
			)
			LEFT JOIN
				shop_order_txnvars sot1
			ON
				shop_orders.id = sot1.order_id
			AND
				sot1.name = 'txn_id'
			LEFT JOIN
				shop_order_txnvars sot2
			ON
				shop_orders.id = sot2.order_id
			AND
				sot2.name = 'cvs'
			WHERE
				shop_user_accounts.id = %u
			AND
				shop_user_accounts.additional_payment_session_id = shop_orders.session_id
		"
			,$user_session->account_id
		)
	);
	$additional_payment = $additional_payment->FetchRow();
	if(trim($additional_payment['reference']) == '')
		$additional_payment = false;
?>