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
		
		$products = $db->Execute(
			sprintf("
				SELECT
					shop_products.id
					,shop_products.name
					,shop_products.price
					,shop_product_options.upc_code ean
				FROM
					shop_products
				LEFT JOIN
					shop_product_options
				ON
					shop_product_options.product_id = shop_products.id
				WHERE
					shop_products.id > 1
			"
			)
		);
		
		header('Content-type: application/force-download');
		header("Content-Disposition: attachment; filename=\"product_export.csv\";" ); 
		
		$csv = array();
		$csv[] = "Name";
		$csv[] = "EAN";
		$csv[] = "Price";
		echo '"'.implode('","', $csv).'"'."\n";
		
		while($row=$products->FetchRow())
		{
			$csv = array();
			$csv[] = $row['name'];
			$csv[] = $row['ean'];
			$csv[] = $row['price'];

			echo '"'.implode('","', $csv).'"'."\n";
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