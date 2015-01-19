<?php
ini_set('memory_limit','128M');
set_time_limit(0);
try 
{
	ob_end_clean();
	header("Content-Type: text/csv");
	header("Cache-Control: no-store, no-cache");
	header(sprintf("Content-Disposition: attachment; filename=order_export_%s.tsv",date('d_m_Y_G_i_s')));

	while($row = $orders->FetchRow())
	{
		$txn_keys = explode('[::]', $row['txn_keys']);
		$txn_values = explode('[::]', $row['txn_values']);
		$txn = array();
		foreach($txn_keys as $index=>$key)
			$txn[$key] = $txn_values[$index];
		
		$delivery_address = explode("\n", $row['delivery_address']);
		$address = explode("\n", $row['address']);
		
		$discount_percentage = round($row['discount_percentage'], 2) * 0.01;
		if($discount_percentage == 0)
			$discount_percentage = '';
		elseif($discount_percentage < 1)
			$discount_percentage = str_replace('0.', '.', sprintf("%.4f", $discount_percentage));
		
		$csv = array();
		$csv[1] = '20';
		$csv[2] = '';
		$csv[3] = '';
		$csv[4] = '';
		$csv[5] = $row['id'];
		$csv[6] = '';
		$csv[7] = date('m/d/Y',$row['time']);
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
		$csv[19] = $row['upc_code'];
		$csv[20] = ''; //$row['width'];
		$csv[21] = ''; //$row['color'];
		$csv[22] = '';
		$csv[23] = ''; //$row['size'];
		$csv[24] = 'USD';
		$csv[25] = number_format($row['order_price'], 2, ".", "");
		$csv[26] = $row['order_quantity'];
		$csv[27] = '';
		$csv[28] = $row['delivery_name'];
		$csv[29] = isset($delivery_address[0])?$delivery_address[0]:'';
		$csv[30] = isset($delivery_address[1])?$delivery_address[1]:'';
		$csv[31] = isset($delivery_address[2])?$delivery_address[2]:'';
		$csv[32] = isset($delivery_address[2])?$delivery_address[3]:'';
		$csv[33] = $row['delivery_country_code'];
		$csv[34] = $row['delivery_postcode'];
		$csv[35] = 'US';
		$csv[36] = $row['delivery_phone'];
		$csv[37] = $row['delivery_email'];
		$csv[38] = $row['shipping_method'];
		$csv[39] = '';
		$csv[40] = '';
		$csv[41] = $row['name'];
		$csv[42] = isset($address[0])?$address[0]:'';
		$csv[43] = isset($address[1])?$address[1]:'';
		$csv[44] = isset($address[2])?$address[2]:'';
		$csv[45] = isset($address[3])?$address[3]:'';
		$csv[46] = $row['country_code'];
		$csv[47] = $row['postcode'];
		$csv[48] = 'US';
		$csv[49] = $row['phone'];
		$csv[50] = '';
		$csv[51] = number_format($row['shipping']+$row['packing'], 2, ".", "");
		$csv[52] = $discount_percentage;
		$csv[53] = 'Y'; // !!!!!!!!!!!!!!
		for($i=54;$i<=74;$i++)
			$csv[$i] = '';
		$csv[75] = ($row['tax']*100)/$row['total'];
				
		echo implode("\t", $csv)."\n";
	}
	exit;
}
catch (Exception $e)
{
	error("There was a problem whilst creating the export, please try again. If this persists please notify your designated support contact.","Report Error");
}
?>