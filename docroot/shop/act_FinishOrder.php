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
				,delivery_state
				,delivery_country
				,delivery_email
				,delivery_phone
				,email
				,tel
				,total
				,discount
				,packing
				,shipping
				,tax
				,vat_rate
				,paid
				,multibuy_discount
				,promotional_discount
				,promotional_discount_type
				,discount_code
				,discount_code_id
				,pick_up_date
				,gift_voucher
				,additional_payment
				,account_id
				,country_id
				,delivery_country_id
				,delivery_service_code
				,gift_payment
				,gift_message
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
				,%s
				,%f
				,%f
				,%s
				,%f
				,%f
				,%f
				,%f
				,%f
				,%f
				,%s
				,%s
				,%u
				,%s
				,%f
				,%u
				,%u
				,%u
				,%u
				,%s
				,%u
				,%s
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
			,$db->Quote($order['delivery_state'])
			,$db->Quote($order['delivery_country'])
			,$db->Quote($order['delivery_email'])
			,$db->Quote($order['delivery_phone'])
			,$db->Quote($order['email'])
			,$db->Quote($order['tel'])
			,$params['vars']['total']
			,$params['vars']['discount']
			,($params['vars']['packing'] !== null)?($params['vars']['packing']+0):'NULL'
			,$params['vars']['shipping']
			,$params['vars']['tax']
			,VAT
			,$order['paid']
			,$params['vars']['multibuy_discount']
			,$params['vars']['promotional_discount']
			,$db->Quote($params['vars']['promotional_discount_type'])
			,$db->Quote($params['vars']['discount_code'])
			,$params['vars']['discount_code_id']
			,$db->Quote($params['vars']['pick_up_date'])
			,$params['vars']['gift_voucher']
			,$params['vars']['additional_payment']
			,$params['vars']['account_id']
			,$params['vars']['billing_country_id']
			,$params['vars']['delivery_country_id']
			,$db->Quote($params['vars']['delivery_service_code'])
			,$params['vars']['last_gift_list_id']?1:0
            ,$db->Quote($params['vars']['gift_message'])
		)
	);
	$db_errno = $db->ErrorNo();
	$debug = array(
		'sql' => $sql
		,'error_no' => $db_errno
		,'error_msg' => $db->ErrorMsg()
	);
	$order_id=$db->Insert_ID();
	$debug['order_id'] = $order_id;
	/*if($db->hasInsertID) {
		$order_id = $db->Execute(sprintf("SELECT `id` FROM `shop_orders` WHERE `session_id` = %s",$db->Quote($session->session_id)));
		$order_id = $order_id->FetchRow();
		$order_id = $order_id['id'];
	}*/
	if(!$order_id || $db_errno)
	{
		if ($handle = fopen($config['path'].'debug.txt', 'a'))
		{
			fwrite($handle, "\n".'===== '.date('d/m/Y H:i:s').' Mysql bug 1 ====='."\n".var_export($debug, true));
			fclose($handle);
		}
		$order_id = 0;
		return;
	}
		
	$order_id = $db->Execute(sprintf("SELECT `id` FROM `shop_orders` WHERE `id` = %u",$order_id));
	$order_id = $order_id->FetchRow();
	$order_id = $order_id?($order_id['id']+0):0;
	if(!$order_id)
	{
		if ($handle = fopen($config['path'].'debug.txt', 'a'))
		{
			fwrite($handle, "\n".'===== '.date('d/m/Y H:i:s').' Mysql bug 2 ====='."\n".var_export($debug, true));
			fclose($handle);
		}
		return;
	}

		
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
					,gift_list_item_id
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
					,%u
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
				,$product['gift_list_item_id']
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