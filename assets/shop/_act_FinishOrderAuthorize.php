<?
	/**
	 * e-Commerce System Data Feed/Export Plugin
	 * Copyright (c) 2002-2006 Philip John, All Rights Reserved.
	 * Author	: Philip John
	 * Version	: 6.0
	 *
	 * PROPRIETARY/CONFIDENTIAL.  Use is subject to license terms.
	 */
?>
<?
	$status=$order['status'];
	$db->Execute(
		$sql = sprintf("
			INSERT INTO shop_orders (
				session_id
				,time
				,name
				,address
				,postcode
				,country
				,delivery_name
				,delivery_address
				,delivery_postcode
				,delivery_country
				,delivery_email
				,delivery_phone
				,email
				,tel
				,total
				,discount
				,packing
				,shipping
				,vat_rate
				,paid
				,multibuy_discount
				,promotional_discount
				,promotional_discount_type
				,discount_code
				,pick_up_date
				,gift_voucher
				,additional_payment
			) VALUES (
				%s
				,%u
				,%s
				,%s
				,%s
				,%s
				,%s
				,%s
				,%s
				,%s
				,%s
				,%s
				,%s
				,%s
				,%f
				,%f
				,%f
				,%f
				,%f
				,%f
				,%f
				,%f
				,%s
				,%s
				,%s
				,%f
				,%u
			)
		"
			,$db->Quote($session->session_id)
			,$order['time']
			,$db->Quote($order['name'])
			,$db->Quote($order['address'])
			,$db->Quote($order['postcode'])
			,$db->Quote($order['country'])
			,$db->Quote($order['delivery_name'])
			,$db->Quote($order['delivery_address'])
			,$db->Quote($order['delivery_postcode'])
			,$db->Quote($order['delivery_country'])
			,$db->Quote($order['delivery_email'])
			,$db->Quote($order['delivery_phone'])
			,$db->Quote($order['email'])
			,$db->Quote($order['tel'])
			,$params['vars']['total']
			,$params['vars']['discount']
			,$params['vars']['packing']
			,$params['vars']['shipping']
			,VAT
			,$order['paid']
			,$params['vars']['multibuy_discount']
			,$params['vars']['promotional_discount']
			,$db->Quote($params['vars']['promotional_discount_type'])
			,$db->Quote($params['vars']['discount_code'])
			,$db->Quote($params['vars']['pick_up_date'])
			,$params['vars']['gift_voucher']
			,$params['vars']['additional_payment']
		)
	);
	$order_id=$db->Insert_ID();
	//echo var_export($sql, true);
	//var_dump($order_id);
	
	//echo $sql;
	if(!$order_id)
		return;
	
	if(is_array($order['txnvars']))
	{
		$keys=array_keys($order['txnvars']);
		foreach($keys AS $txnvar)
		{
			$db->Execute(
				$sql = sprintf("
					INSERT INTO
						shop_order_txnvars (
							order_id
							,name
							,value
						) VALUES (
							%u
							,%s
							,%s
						)
				"
					,$order_id
					,$db->Quote($txnvar)
					,$db->Quote($order['txnvars'][$txnvar])
				)
			);
			//echo var_export($sql, true);
		}
	}

	foreach($products as $product)
	{
		$db->Execute(
			$sql = sprintf("
				INSERT INTO shop_order_products (
					order_id
					,product_id
					,quantity
					,option_id
					,custom
					,price
					,discount
					,promotional_discount
					,weight
				) VALUES (
					%u
					,%u
					,%u
					,%u
					,%s
					,%f
					,%f
					,%f
					,%f
				)
			"
				,$order_id
				,$product['product_id']
				,$product['quantity']
				,$product['option_id']
				,$db->Quote($product['custom'])
				,$product['price']
				,$product['discount']
				,$product['promotional_discount']
				,$product['weight']
			)
		);
		
		$db->Execute(
			$sql = sprintf("
				UPDATE
					shop_product_options
				SET
					quantity = quantity - %u
				WHERE
					id = %u
			"
				,$product['quantity']
				,$product['option_id']
			)
		);
		//echo var_export($sql, true);
	}
	
	if($params['vars']['account_id']+0)
	{
		$db->Execute(
			$sql = sprintf("
				INSERT INTO 
					shop_user_orders
				SET
					account_id = %u
					,order_id = %u
			"
				,$params['vars']['account_id']
				,$order_id
			)
		);
	}
	
	if($params['vars']['promotional_discount'])
	{
		$discount_code=$db->Execute(
			$sql=sprintf("
				SELECT
					shop_user_promotional_codes.id supc_id
				FROM
				(
					shop_promotional_codes
					,shop_user_promotional_codes
				)
				WHERE
					shop_promotional_codes.code = %s
				AND
					shop_user_promotional_codes.code_id = shop_promotional_codes.id
				AND
					shop_user_promotional_codes.account_id = %u
				AND
					shop_user_promotional_codes.order_id = 0
			"
				,$db->Quote($params['vars']['discount_code'])
				,$params['vars']['account_id']
			)
		);
		if($discount_code = $discount_code->FetchRow())
		{
			$db->Execute(
				$sql = sprintf("
					UPDATE
						shop_user_promotional_codes
					SET
						order_id=%u
					WHERE
						id=%u
				"
					,$order_id
					,$discount_code['supc_id']
				)
			);
		}
	}