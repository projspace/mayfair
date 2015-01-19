<?
	while($fc_order = $fc_orders->FetchRow())
	{
		$txn_keys = explode('[::]', $fc_order['txn_keys']);
		$txn_values = explode('[::]', $fc_order['txn_values']);
		$txn = array();
		foreach($txn_keys as $index=>$key)
			$txn[$key] = $txn_values[$index];
		
		$delivery_address = explode("\n", $fc_order['delivery_address']);
		$address = explode("\n", $fc_order['address']);
		
		$discount_percentage = round($fc_order['discount_percentage'], 2) * 0.01;
		if($discount_percentage == 0)
			$discount_percentage = '';
		elseif($discount_percentage < 1)
			$discount_percentage = str_replace('0.', '.', sprintf("%.4f", $discount_percentage));
			
		$csv = array();
		$csv[1] = '20';
		$csv[2] = '';
		$csv[3] = '';
		$csv[4] = '';
		$csv[5] = $fc_order['id'];
		$csv[6] = '';
		$csv[7] = date('m/d/Y',$fc_order['time']);
		$csv[8] = '';
		$csv[9] = '';
		$csv[10] = '';
		$csv[11] = '';
		$csv[12] = isset($txn['txn_id'])?$txn['txn_id']:'';
		$csv[13] = isset($txn['auth_code'])?$txn['auth_code']:'';
		$csv[14] = '';
		$csv[15] = isset($txn['account_number'])?$txn['account_number']:'';
		$csv[16] = '';
		$csv[17] = '';
		$csv[18] = '';
		$csv[19] = $fc_order['upc_code'];
		$csv[20] = ''; //$fc_order['width'];
		$csv[21] = ''; //$fc_order['color'];
		$csv[22] = '';
		$csv[23] = ''; //$fc_order['size'];
		$csv[24] = 'USD';
		$csv[25] = number_format($fc_order['order_price'], 2, ".", "");
		$csv[26] = $fc_order['order_quantity'];
		$csv[27] = '';
		$csv[28] = $fc_order['delivery_name'];
		$csv[29] = isset($delivery_address[0])?$delivery_address[0]:'';
		$csv[30] = isset($delivery_address[1])?$delivery_address[1]:'';
		$csv[31] = isset($delivery_address[2])?$delivery_address[2]:'';
		$csv[32] = isset($delivery_address[2])?$delivery_address[3]:'';
		$csv[33] = $fc_order['delivery_country_code'];
		$csv[34] = $fc_order['delivery_postcode'];
		$csv[35] = 'US';
		$csv[36] = $fc_order['delivery_phone'];
		$csv[37] = $fc_order['delivery_email'];
		$csv[38] = $fc_order['shipping_method'];
		$csv[39] = '';
		$csv[40] = '';
		$csv[41] = $fc_order['name'];
		$csv[42] = isset($address[0])?$address[0]:'';
		$csv[43] = isset($address[1])?$address[1]:'';
		$csv[44] = isset($address[2])?$address[2]:'';
		$csv[45] = isset($address[3])?$address[3]:'';
		$csv[46] = $fc_order['country_code'];
		$csv[47] = $fc_order['postcode'];
		$csv[48] = 'US';
		$csv[49] = $fc_order['phone'];
		$csv[50] = '';
		$csv[51] = number_format($fc_order['shipping']+$fc_order['packing'], 2, ".", "");
		$csv[52] = $discount_percentage;
		$csv[53] = 'Y'; // !!!!!!!!!!!!!!
		for($i=54;$i<=74;$i++)
			$csv[$i] = '';
		$csv[75] = ($fc_order['tax']*100)/$fc_order['total'];
		
		$filename = $config['full_circle']['company_number'].'_order_'.$fc_order['id'].'.txt';
		if ($handle = fopen($source = $config['path'].'orders/'.$filename, 'a')) 
		{
			fwrite($handle, implode("\t", $csv)."\n");
			fclose($handle);
		}
		
		$time = time();
		$archive = $config['path'].'script/archive/site2fc/'.date('Y-m-d', $time).'/';
		if(!is_dir($archive))
		{
			@mkdir($archive, 0777);
			@chmod($archive, 0777);
		}
		$archive .= $filename.'_'.date('Y-m-d-H.i.s', $time).'.txt';
		@copy($source, $archive);
	}
?>