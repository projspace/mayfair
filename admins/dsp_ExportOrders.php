<?
	$filename = 'downloads/order_export/weborders-'.date('Y-m-d_H_i_s').'.csv';
	if (!$handle = fopen($config['path'].$filename, 'w')) 
	{
		error("Unable to create csv report. Please try again later.","Error");
		return;
    }
	
	$csv = array();
	$csv[] = "Till Number";
	$csv[] = "Operator Name";
	$csv[] = "Date of transaction";
	$csv[] = "Time of Transaction";
	$csv[] = "Transaction Number";
	$csv[] = "PLU";
	$csv[] = "PLU Desc";
	$csv[] = "Quantiy Sold";
	$csv[] = "RRP";
	$csv[] = "Vat Amount";
	$csv[] = "Price Paid";
	$csv[] = "Payment Made";
	fwrite($handle, ''.implode(',', $csv).''."\n");
	
	while($row=$orders->FetchRow())
	{
		$csv = array();
		$csv[] = '4';
		$csv[] = 'E-shop';
		$csv[] = date('d/m/Y', $row['time']);
		$csv[] = date('H:i:s', $row['time']);
		$csv[] = $row['id'];
		$csv[] = str_replace('"', '\"', trim($row['code']));
		$csv[] = str_replace('"', '\"', trim($row['name']));
		$csv[] = $row['quantity'];
		
		if($row['vat'])
		{
			$csv[] = $row['price']/(1+$row['vat_rate']*0.01);
			$csv[] = $row['price'] - $row['price']/(1+$row['vat_rate']*0.01);
		}
		else
		{
			$csv[] = $row['price'];
			$csv[] = '0';
		}
		$csv[] = $row['price'];
		$csv[] = '4';
		fwrite($handle, ''.implode(',', $csv).''."\n");
	}
	fclose($handle);
	
	$db->Execute(
		sprintf("
			UPDATE
				shop_variables
			SET
				value = %u
			WHERE
				name = 'last_order_export'
		"
			,time()
		)
	);
	
	header('Content-type: application/force-download');
	header("Content-Disposition: attachment; filename=\"weborders.csv\";" ); 
	readfile($config['path'].$filename);
	exit;
?>