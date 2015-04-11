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
		
		$cfg_import['start_time'] = time();
		$cfg_import['import_id'] = uuid();

		$cfg_import['log_file'] = true;
		$cfg_import['filename_log'] = $config['path'].'script/logs/commission/'.date('d.m.Y-H.i.s', $cfg_import['start_time']).'-'.$cfg_import['import_id'].'.log';
		
		function import_log($text, $print = false)
		{
			global $cfg_import;
			$date = date('d/m/Y H:i:s');
			
			if($cfg_import['log_file'])
			{
				if ($handle = @fopen($cfg_import['filename_log'], 'a'))
				{
					fwrite($handle, "\n".'===================='.$date.'===================='."\n".$text."\n");
					fclose($handle);
				}
				else
					throw new Exception('Unable to open log file "'.$cfg_import['filename_log'].'"');
			}
			if($print)
				echo "<br />\n".'===================='.$date.'===================='."<br />\n<pre>".$text."</pre><br />\n";
		}
		
		$log_url = str_replace($config['path'], $config['dir'], $cfg_import['filename_log']);
		echo "<br />\nLog file: <a href='".$log_url."'>".$log_url."</a><br />\n";
		
		$today = array();
		$today['start'] = mktime(0, 0, 0, date('n'), date('j'), date('Y'));
		$today['end'] = $today['start'] + 86400 - 1;
		
		$results=$db->Execute(
			sprintf("
				SELECT
					*
				FROM
					shop_variables
				WHERE
					name IN ('cron_orders_distance','cron_orders_commission','cron_orders_period')
			"
			)
		);
		while($row = $results->FetchRow())
			$$row['name'] = $row['value'];
			
		$db->SetTransactionMode("SERIALIZABLE");
		$db->StartTrans();
			
		$orders = $db->Execute(
			$sql = sprintf("
				SELECT
					shop_orders.*
				FROM
					shop_orders
				LEFT JOIN
				(
					shop_user_orders
					,shop_user_accounts
				)
				ON
					shop_user_orders.order_id = shop_orders.id
				AND
					shop_user_orders.account_id = shop_user_accounts.id
				AND
				(
					shop_user_accounts.teacher = 1
				OR
					shop_user_accounts.shop = 1
				)
				LEFT JOIN
					shop_order_products sop1
				ON
					sop1.order_id = shop_orders.id
				LEFT JOIN
					shop_order_products sop2
				ON
					sop2.order_id = shop_orders.id
				AND
					sop2.refunded = 1
				WHERE
					%u <= shop_orders.time
				AND
					shop_orders.time <= %u
				AND
					shop_orders.paid != shop_orders.refunded
				AND
					shop_orders.promotional_discount = 0
				AND
					shop_user_accounts.id IS NULL
				GROUP BY
					shop_orders.id
				HAVING
					COUNT(DISTINCT sop2.id) < COUNT(DISTINCT sop1.id)
			"
				,$today['start'] - $cron_orders_period * 86400
				,$today['end'] - $cron_orders_period * 86400
			)
		);
		while($order = $orders->FetchRow())
		{
			$commission = ($order['total'] - $order['refunded']) * $cron_orders_commission / 100;
			
			$postcode = trim($order['delivery_postcode']);
			if($postcode == '')
			{
				import_log("Postcode empty for order ".$order['id'], true);
				continue;
			}
				
			if(!($data = get_google_coords($postcode)))
			{
				import_log("Unable to get google maps latitude and longitude for order ".$order['id'], true);
				continue;
			}
			
			$lat = $data['lat']+0;
			$long = $data['long']+0;
			
			import_log('Lat: '.$lat."\n".'Long: '.$long);
			
			$earth_radius = 3963.0; //miles
			/*$earth_radius = 6378.7; //kilometers
			$earth_radius = 3437.74677; //nautical miles*/
			$radians = 180/M_PI;
			
			$db->Execute(
				sprintf("
					DELETE FROM
						shop_user_shop_commissions
					WHERE
						order_id=%u
				"
					,$order['id']
				)
			);
			
			$shops=$db->Execute(
				$sql = sprintf("
					SELECT
						id
						,rating
					FROM
						shop_user_shops
					WHERE
						%1\$f * ACOS(SIN(lat/%2\$f) * SIN(%3\$f/%2\$f) + COS(lat/%2\$f) * COS(%3\$f/%2\$f) * COS(%4\$f/%2\$f - `long`/%2\$f)) <= %5\$f
					AND
						hidden = 0
				"
					,$earth_radius
					,$radians
					,$lat
					,$long
					,$cron_orders_distance
				)
			);
			import_log($sql);
			$ratings = array();
			while($shop = $shops->FetchRow())
				$ratings[$shop['id']] = $shop['rating'];
				
			import_log('Ratings: '.var_export($ratings, true));
				
			$sql_insert = array();
			$sum = array_sum($ratings);
			if($sum)
				$commission_unit = $commission/$sum;
			else
				$commission_unit = 0;
				
			import_log('Commission: '.$commission."\n".'Rating sum: '.$sum."\n".'Commission unit: '.$commission_unit);
				
			foreach($ratings as $shop_id=>$rating)
				$sql_insert[] = sprintf("(%u,%u,%f,%f,%f)", $order['id'], $shop_id, $commission_unit*$rating, $cron_orders_commission, $cron_orders_distance);
			if(count($sql_insert))
				$db->Execute("INSERT INTO shop_user_shop_commissions (order_id,shop_id,amount,commission,distance) VALUES ".implode(',', $sql_insert));
		}
		
		$ok=$db->CompleteTrans();
		if(!$ok)
			throw new Exception("Database error:\nerror no: ".$db->ErrorNo()."\nerror msg: ".$db->ErrorMsg());
		
		import_log('Ending succesfully', true);
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