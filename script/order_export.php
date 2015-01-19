<?
	error_reporting(E_ALL);
	ini_set('display_errors','1');
	set_time_limit(0);
	try 
	{
		include("../lib/cfg_Config.php");
		include("../lib/adodb/adodb.inc.php");
		include("../lib/act_OpenDB.php");
		include("../lib/lib_Common.php");
		
		$sql_filter = array();
	
		//$sql_filter[] = sprintf("shop_orders.processed > 0");
		$sql_filter[] = sprintf('shop_orders.`time` >= %u', strtotime('2012-06-19 00:00:00'));
		$sql_filter[] = sprintf('shop_orders.`time` <= %u', strtotime('2012-07-27 23:59:59'));
		if(count($sql_filter))
			$sql_filter = implode(' AND ', $sql_filter);
		else
			$sql_filter = '1';
		
		$orders=$db->Execute(
			$sql = sprintf("
				SELECT
					shop_orders.*
				FROM
					shop_orders
				WHERE
					%s
				ORDER BY
					shop_orders.time DESC
			"
				,$sql_filter
			)
		);
		
		$products=$db->Execute(
			$sql = sprintf("
				SELECT DISTINCT
					shop_order_products.*
					,shop_products.code
				FROM
					shop_orders
				LEFT JOIN
					shop_order_products
				ON
					shop_order_products.order_id = shop_orders.id
				LEFT JOIN
					shop_products
				ON
					shop_order_products.product_id = shop_products.id
				WHERE
					%s
				ORDER BY
					shop_orders.time DESC
			"
				,$sql_filter
			)
		);
		$products = $products->GetRows();
				
		header('Content-type: application/force-download');
		header("Content-Disposition: attachment; filename=\"order_export_usa_dev.csv\";" ); 
		
		$csv = array();
		$csv[] = "Order ID";
		$csv[] = "Order Date";
		$csv[] = "Order Amount";
		$csv[] = "Discount";
		$csv[] = "Tax";
		$csv[] = "Shipping";
		$csv[] = "Packing";
		$csv[] = "Order Total";
		$csv[] = "Order Refunded";
		$csv[] = "Product Style";
		$csv[] = "Product Quantity";
		echo '"'.implode('","', $csv).'"'."\n";
		
		while($row=$orders->FetchRow())
		{
			$csv = array();
			$csv[] = $row['id'];
			$csv[] = date('d/m/Y H:i', $row['time']);
			$csv[] = round($row['total'], 2);
			$csv[] = round($row['promotional_discount']+$row['discount']+$row['multibuy_discount'], 2);
			$csv[] = round($row['tax'], 2);
			$csv[] = round($row['shipping'], 2);
			$csv[] = round($row['packing'], 2);
			$csv[] = round($row['paid'], 2);
			$csv[] = round($row['refunded'], 2);
			
			foreach($products as $prod)
			{
				if($prod['order_id'] != $row['id'])
					continue;
					
				$csv2 = $csv;
				$csv2[] = $prod['code'];
				$csv2[] = $prod['quantity'];
				echo '"'.implode('","', $csv2).'"'."\n";
			}
		}
		flush();
		exit;
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