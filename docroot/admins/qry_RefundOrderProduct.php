<?
	/**
	 * e-Commerce System
	 * Copyright (c) 2002-2006 Philip John, All Rights Reserved.
	 * Author	: Philip John
	 * Version	: 6.0
	 *
	 * PROPRIETARY/CONFIDENTIAL.  Use is subject to license terms.
	 */
?>
<?
	$order=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				shop_orders
			WHERE
				id=%u
		"
			,$_REQUEST['order_id']
		)
	);
	$order = $order->FetchRow();
	
	$txnvars=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				shop_order_txnvars
			WHERE
				order_id=%u
			ORDER BY
				id
			ASC
		"
			,$_REQUEST['order_id']
		)
	);
	$txnvars=get_txnvars($txnvars->GetRows());

	$product=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				shop_order_products
			WHERE
				order_id=%u
			AND
				product_id=%u
			AND
				refunded = 0
		"
			,$_REQUEST['order_id']
			,$_REQUEST['product_id']
		)
	);
	$product = $product->FetchRow();
?>