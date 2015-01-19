<?
	try 
	{
		$cfg_import['start_time'] = time();
		$cfg_import['import_id'] = uuid();
		$cfg_import['filename_import'] = $config['path'].'confirmation/'.$filename;
		
		$cfg_import['archive_import_file'] = true;
		$cfg_import['filename_archive'] = $config['path'].'script/archive/fc/'.date('Y-m-d', $cfg_import['start_time']).'/';
		if(!is_dir($cfg_import['filename_archive']))
		{
			@mkdir($cfg_import['filename_archive'], 0777);
			@chmod($cfg_import['filename_archive'], 0777);
		}
		$cfg_import['filename_archive'] .= $filename.'_'.date('Y-m-d-H.i.s', $cfg_import['start_time']).'-'.$cfg_import['import_id'].'.txt';
		
		$cfg_import['log_file'] = true;
		$cfg_import['filename_log'] = $config['path'].'script/logs/fc/'.date('Y-m-d', $cfg_import['start_time']).'/';
		if(!is_dir($cfg_import['filename_log']))
		{
			@mkdir($cfg_import['filename_log'], 0777);
			@chmod($cfg_import['filename_log'], 0777);
		}
		$cfg_import['filename_log'] .= $filename.'_'.date('Y-m-d-H.i.s', $cfg_import['start_time']).'-'.$cfg_import['import_id'].'.log';
		
		echo "<br />\nLog file: ".$cfg_import['filename_log']."<br />\n";
		
		import_log('Start import', true);
		
		if(!file_exists($cfg_import['filename_import']))
			throw new Exception('Import file does not exist.');
			
		if(!is_readable($cfg_import['filename_import']))
			throw new Exception('Import file does not have read permission.');
			
		$archived = false;
		if($cfg_import['archive_import_file'])
		{
			if(!copy($cfg_import['filename_import'], $cfg_import['filename_archive']))
				throw new Exception('Cannot archive import file');
			$archived = true;
		}
		
		$fp = fopen($cfg_import['filename_import'], 'r');
		if(!$fp)
			throw new Exception('Cannot open import file');

		if(!flock($fp, LOCK_EX))
			throw new Exception('Cannot lock import file');
		
		$db->SetTransactionMode("SERIALIZABLE");
		$db->StartTrans();
		
		$order_ids = array();
		$line = 0;
		while(($data = fgetcsv($fp, 0, "\t")) !== false)
		{
			$line++;
			/*if($line == 1)
				continue;*/
				
			$data = array_map('trim', $data);
			
			$product=$db->Execute(
				sprintf("
					SELECT
						shop_order_products.*
					FROM
						shop_order_products
					JOIN
						shop_product_options
					ON
						shop_product_options.id = shop_order_products.option_id
					WHERE
						shop_order_products.order_id=%u
					AND
						shop_product_options.upc_code = %s
				"
					,$data[2]
					,$db->Quote($data[9])
				)
			);
			$product = $product->FetchRow();
			if(!$product)
			{
				import_log("Product not found: order id: ".$data[2]." upc code: ".$data[9], true);
				continue;
			}
			
			$order_ids[] = $data[2]+0;
			
			$db->Execute(
				sprintf("
					UPDATE
						shop_order_products
					SET
						shipped = %u
					WHERE
						id=%u
					LIMIT 1
				"
					,$data[14]
					,$product['id']
				)
			);
		}
		
		fclose($fp);
		
		if(!count($order_ids))
			$order_ids[] = 0;
			
		$orders=$db->Execute(
			sprintf("
				SELECT
					shop_orders.*
					,COUNT(DISTINCT sop.id) not_fc_processed
					,shop_order_txnvars.value transaction_id
				FROM 
					shop_orders
				LEFT JOIN
					shop_order_products sop
				ON
					sop.order_id = shop_orders.id
				AND
					sop.shipped IS NULL
				LEFT JOIN
					shop_order_txnvars
				ON
					shop_order_txnvars.order_id = shop_orders.id
				AND
					shop_order_txnvars.name = 'txn_id'
				WHERE
					shop_orders.id IN (%s)
				GROUP BY
					shop_orders.id
			"
				,implode(',', $order_ids)
			)
		);
		while($order = $orders->FetchRow())
		{
			/*if($order['not_fc_processed']+0)
			{
				import_log("Order ignored....not all products were processed by FC: order id: ".$order['id'], true);
				continue;
			}
			else*/
				import_log("Processing order ".$order['id'], true);
			
			$products=$db->Execute(
				sprintf("
					SELECT
						shop_order_products.*
						,(shop_products.exclude_discounts OR shop_categories.exclude_discounts) exclude_discounts
						,shop_products.name
						,shop_products.no_shipping
						,shop_products.pick_up_only
						,shop_product_options.upc_code
						,shop_sizes.name size
						,shop_widths.name width
						,shop_colors.name color
					FROM
						shop_order_products
					LEFT JOIN
					(
						shop_products
						,shop_categories
					)
					ON
						shop_products.id = shop_order_products.product_id
					AND
						shop_categories.id=shop_products.category_id
					LEFT JOIN
						shop_product_options
					ON
						shop_product_options.id = shop_order_products.option_id
					AND
						shop_product_options.product_id = shop_order_products.product_id
					LEFT JOIN
						shop_sizes
					ON
						shop_sizes.id = shop_product_options.size_id
					LEFT JOIN
						shop_widths
					ON
						shop_widths.id = shop_product_options.width_id
					LEFT JOIN
						shop_colors
					ON
						shop_colors.id = shop_product_options.color_id
					WHERE
						shop_order_products.order_id=%u
				"
					,$order['id']
				)
			);
			$products = $products->GetRows();
			$modified = true;
			/*$modified = false;
			foreach($products as $row)
				if($row['quantity']+0 != $row['shipped']+0)
				{
					$modified = true;
					break;
				}*/
			
			if($modified)
			{
				import_log("Different shipping", true);
				$getcountry=$db->Execute(
					$sql = sprintf("
						SELECT
							shop_countries.name
							,shop_areas.name AS area_name
							,shop_areas.over_weight_unit
							,shop_areas.over_price
							,shop_areas.free_shipping
							,shop_countries.area_id
							,shop_countries.price
							,shop_countries.minimal_price
						FROM
							shop_countries
							,shop_areas
						WHERE
							shop_countries.id=%u
						AND
							shop_countries.area_id=shop_areas.id
					"
						,$order['delivery_country_id']
					)
				);
				$getcountry = $getcountry->FetchRow();
				
				$country=$getcountry['name'];
				$area=$getcountry['area_name'];
				$area_id=$getcountry['area_id'];
				
				$area_prices=$db->Execute(
					sprintf("
						SELECT
							*
						FROM
							shop_area_prices
						WHERE
							area_id = %u
						ORDER BY
							weight ASC
					"
						,$area_id
					)
				);
			
				$prods = array();
				$vars = array();
				$vars['total'] = 0;
				$vars['weight'] = 0;
				$vars['promotional_discount'] = 0;
				$vars['total_discount_percentage'] = 0;
				$shippable = 0;
				foreach($products as $key=>$row)
				{
					$row['item_price']=$row['price']-$row['discount'];
					$products[$key]['item_price']=$row['item_price'];
					$products[$key]['total']=$row['shipped']*$row['item_price'];
					$vars['total']+=$row['shipped']*$row['item_price'];
					
					if(!$row['exclude_discounts'])
						$vars['total_discount_percentage'] += $row['shipped']*$row['item_price'];
						
					if(!$row['no_shipping'] && !$row['pick_up_only'])
					{
						$vars['weight']+=$row['shipped']*$row['weight'];
						$shippable++;
					}
				}
				
				/* DISCOUNT CODE */
				if($order['discount_code'] != '')
				{
					$results=$db->Execute(
						$sql = sprintf("
						(
							SELECT
								shop_promotional_codes.*
							FROM
							(
								shop_promotional_codes
								,shop_user_promotional_codes
							)
							LEFT JOIN
								shop_user_promotional_codes supc
							ON
								supc.code_id = shop_promotional_codes.id
							AND
								supc.account_id = %u
							AND
								supc.order_id != 0
							WHERE
								shop_promotional_codes.code = %s
							AND
								shop_promotional_codes.deleted = 0
							AND
								shop_promotional_codes.suspended = 0
							AND
								shop_promotional_codes.all_users = 0
							AND
								IF(shop_promotional_codes.expiry_date, CURDATE() < shop_promotional_codes.expiry_date, 1)
							AND
								shop_user_promotional_codes.code_id = shop_promotional_codes.id
							AND
								shop_user_promotional_codes.account_id = %u
							AND
								shop_user_promotional_codes.order_id = 0
							AND
								IF(shop_promotional_codes.value_type = 'percent', %f, %f) >= shop_promotional_codes.min_order
							GROUP BY
								shop_promotional_codes.id
							HAVING
								COUNT(DISTINCT supc.order_id) < shop_promotional_codes.use_count
						)
						UNION ALL
						(
							SELECT
								shop_promotional_codes.*
							FROM
								shop_promotional_codes
							LEFT JOIN
								shop_user_promotional_codes supc
							ON
								supc.code_id = shop_promotional_codes.id
							AND
								supc.account_id = %u
							AND
								supc.order_id != 0
							WHERE
								shop_promotional_codes.code = %s
							AND
								shop_promotional_codes.deleted = 0
							AND
								shop_promotional_codes.suspended = 0
							AND
								shop_promotional_codes.all_users = 1
							AND
								IF(shop_promotional_codes.expiry_date, CURDATE() < shop_promotional_codes.expiry_date, 1)
							AND
								IF(shop_promotional_codes.value_type = 'percent', %f, %f) >= shop_promotional_codes.min_order
							GROUP BY
								shop_promotional_codes.id
							HAVING
								COUNT(DISTINCT supc.order_id) < shop_promotional_codes.use_count
						)
						"
							,$order['account_id']
							,$db->Quote($order['discount_code'])
							,$order['account_id']
							,$vars['total_discount_percentage']
							,$vars['total']
							,$order['account_id']
							,$db->Quote($order['discount_code'])
							,$vars['total_discount_percentage']
							,$vars['total']
						)
					);
					if($row = $results->FetchRow())
					{
						$vars['promotional_discount_type'] = $row['value_type'];
						$vars['discount_code_id'] = $row['id'];
						
						if($row['value_type'] == 'percent')
						{
							foreach($products as $key=>$prod)
								if(!$prod['exclude_discounts'])
									$products[$key]['promotional_discount'] = $row['value'] * (($prod['item_price']+0)/100);
								else
									$products[$key]['promotional_discount'] = 0;
									
							$vars['promotional_discount'] += round($row['value'] * (($vars['total_discount_percentage']+0)/100), 2);
						}
						else
							$vars['promotional_discount'] += $row['value'];
							
						$vars['total'] -= $vars['promotional_discount'];
					}
				}
				
				/* SHIPPING */
				if($shippable && !($vars['total'] >= $getcountry['free_shipping'] && $getcountry['free_shipping'] > 0))
				{
					if($order['delivery_service_code'] != '')
					{
						$shipping = new WSShipping($config, $db);
						$delivery = explode("\n", $order['delivery_address']);
						$delivery_service = $shipping->getService(array(
							'delivery' => array(
								'name' => $order['delivery_name']
								,'line' => $delivery[0]
								,'city' => $delivery[3]
								,'postcode' => $order['delivery_postcode']
							)
							,'weight' => $vars['weight']
							,'total' => $vars['total']
							,'service_code' => $order['delivery_service_code']
						));
						$vars['shipping']=$delivery_service['price'];
					}
					else
						$vars['shipping']=false;
				}
				else
					$vars['shipping']=0;
					
				if($vars['shipping'] === false)
				{
					import_log("Order ignored....shipping couldn't be calculated: order id: ".$order['id'], true);
					continue;
				}
				
				$vars['packing']=$order['packing'];
				
				/* TAX */
				if(in_array($order['delivery_country_id']+0, array(5))) //delivery in California
				{
					$ws_tax = new WSTax($config);
					$prods = array();
					foreach($products as $row)
						$prods[] = array(
							'id' => $row['product_id']
							//,'sales_amount' => $row['total']
							,'sales_amount' => ($row['item_price'] - $row['promotional_discount']) * ($row['shipped'] + 0)
							,'unit_price' => $row['item_price'] - $row['promotional_discount']
							,'upc_code' => $row['upc_code']
							,'quantity' => $row['shipped']+0
						);

					$delivery = explode("\n", $order['delivery_address']);
					$delivery = array(
						'street' => $delivery[0]
						,'city' => $delivery[3]
						,'postcode' => $order['delivery_postcode']
						,'country' => $order['delivery_country']
					);

					$billing = array(
						'name' => $order['name']
					);
					
					$vars['tax'] = $ws_tax->postInvoice(array('time'=>$order['time'],'invoice_number'=>$config['companyshort'].$order['id']),$prods,$delivery,$billing);
				}
				else
					$vars['tax'] = 0;
					
				if($vars['tax'] === false)
				{
					import_log("Order ignored....tax couldn't be calculated: order id: ".$order['id'], true);
					continue;
				}
					
				$params = array(
					'vars' => array(
						'total' => $vars['total']
						,'shipping' => $vars['shipping']
						,'packing' => $vars['packing']
						,'tax' => $vars['tax']
					)
				);
			}
			else
			{
				$params = array(
					'vars' => array(
						'total' => $order['total']
						,'shipping' => $order['shipping']
						,'packing' => $order['packing']
						,'tax' => $order['tax']
						,'promotional_discount' => $order['promotional_discount']
					)
				);
			}

			$params['transaction_id'] = $order['transaction_id'];
			$psp =& new $config['psp']['driver']($config,$smarty,$db);
			if(!$psp->captureTransaction($params, $error_reason))
			{
				import_log("Could not capture transaction for order id: ".$order['id']."; reason: ".var_export($error_reason, true), true);
				continue;
			}
			
			if($modified)
			{
				$db->Execute(
					sprintf("
						UPDATE
							shop_orders
						SET
							total = %f
							,shipping = %f
							,tax = %f
							,promotional_discount = %f
							,paid = %f
							,captured = %u
							,processed = %u
						WHERE
							id=%u
						LIMIT 1
					"
						,$vars['total']
						,$vars['shipping']
						,$vars['tax']
						,$vars['promotional_discount']
						,$vars['total']+$vars['shipping']+$vars['packing']+$vars['tax']
						,time()
						,time()
						,$order['id']
					)
				);
				foreach($products as $row)
					if($row['quantity']+0 != $row['shipped']+0)
					{
						if($row['shipped']+0 == 0)
							$db->Execute(
								sprintf("
									DELETE FROM
										shop_order_products
									WHERE
										id=%u
									LIMIT 1
								"
									,$row['id']
								)
							);
						else
							$db->Execute(
								sprintf("
									UPDATE
										shop_order_products
									SET
										quantity = %u
										,promotional_discount = %f
									WHERE
										id=%u
									LIMIT 1
								"
									,$row['shipped']
									,$row['promotional_discount']*$row['shipped']
									,$row['id']
								)
							);
							
						$db->Execute(
							sprintf("
								INSERT INTO
									shop_order_wishlist
								SET
									order_id = %u
									,product_id = %u
									,option_id = %u
									,quantity = %u
							"
								,$row['order_id']
								,$row['product_id']
								,$row['option_id']
								,$row['quantity'] - $row['shipped']
							)
						);
					}
			}
			else
				$db->Execute(
					sprintf("
						UPDATE
							shop_orders
						SET
							captured = %u
							,processed = %u
						WHERE
							id=%u
						LIMIT 1
					"
						,time()
						,time()
						,$order['id']
					)
				);
			
			if(!$mail->sendMessage(array('order'=>$order, 'params'=>$params, 'products'=>$products),"OrderCapture",$order['email'],$order['name']))
			{
				import_log("Could not send email for order id: ".$order['id'], true);
				continue;
			}
		}
			
		$ok=$db->CompleteTrans();
		if(!$ok)
			throw new Exception("Database error:\nerror no: ".$db->ErrorNo()."\nerror msg: ".$db->ErrorMsg());
		
		@unlink($cfg_import['filename_import']);
		
		import_log('Import ended succesfully', true);
	}
	catch (Exception $e)
	{
		$msg = 'Caught exception: '."\n";
		$msg .= 'message: '.$e->getMessage()."\n";
		$msg .= 'code: '.$e->getCode()."\n";
		$msg .= 'file: '.$e->getFile()."\n";
		$msg .= 'line: '.$e->getLine()."\n";
		$msg .= 'trace string: '.$e->getTraceAsString()."\n";
		$msg .= 'trace array: '.var_export($e->getTrace(), true);
		import_log($msg, true);
	}
?>