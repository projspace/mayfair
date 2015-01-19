<?
	$date_from = safe($_REQUEST['date']['from']);
	$date_to = safe($_REQUEST['date']['to']);
	if($date_from != '' || $date_to != '')
	{
		if($date_from != '')
		{
			$date_from = strtotime(implode('-', array_reverse(explode('/', $date_from))));
			if($date_from === -1)
				$date_from = false;
		}
		else
			$date_from = false;
			
		if($date_to != '')
		{
			$date_to = strtotime(implode('-', array_reverse(explode('/', $date_to))));
			if($date_to === -1)
				$date_to = false;
			else
				$date_to += 86400 - 1;
		}
		else
			$date_to = false;
	}
	else
	{
		switch(safe($_REQUEST['date']['custom']))
		{
			case 'previous_month':
				$date_from = strtotime(date('Y-m-01', mktime(0, 0, 0, date('n'), 0, date('Y'))));
				$date_to = strtotime(date('Y-m-t', mktime(0, 0, 0, date('n'), 0, date('Y'))));
				break;
			case 'this_month':
				$date_from = strtotime(date('Y-m-01'));
				$date_to = strtotime(date('Y-m-t'));
				break;
			case 'all':
				$date_from = 0;
				$date_to = strtotime(date('Y-m-d'));
				break;
			case '7_days':
			default:
				$_REQUEST['date']['custom'] = $_GET['date']['custom'] = '7_days';
				$date_from = strtotime(date('Y-m-d'))-6*86400;
				$date_to = strtotime(date('Y-m-d'));
				break;
		}
		$date_to += 86400 - 1;
	}
		
	$date_from += 0;
	$date_to += 0;
		
	$sql_filter = array();
	
	$sql_filter[] = sprintf("shop_orders.processed > 0");
	$sql_filter[] = sprintf("shop_order_products.refunded = 0");
	$sql_filter[] = sprintf("shop_orders.paid > shop_orders.refunded");
	// custom filters
	if($date_from)
		$sql_filter[] = sprintf('shop_orders.`time` >= %u', $date_from);
	if($date_to)
		$sql_filter[] = sprintf('shop_orders.`time` <= %u', $date_to);
	if($keyword = trim($_REQUEST['keyword']))
		$sql_filter[] = sprintf('(shop_orders.id = %u OR shop_orders.name LIKE %s)', $keyword+0, $db->Quote('%'.$keyword.'%'));
		
	if(count($sql_filter))
		$sql_filter = implode(' AND ', $sql_filter);
	else
		$sql_filter = '1';
		
	$products=$db->Execute(
		sprintf("
			SELECT
				shop_products.id
				,shop_products.name
				,shop_products.code
				,SUM(shop_order_products.quantity) quantity
				,SUM(shop_order_products.quantity * shop_order_products.price) total
			FROM
				shop_order_products
				,shop_products
				,shop_orders
			WHERE
				shop_order_products.product_id = shop_products.id
			AND
				shop_order_products.order_id = shop_orders.id
			AND
				%s
			GROUP BY
				shop_products.id
		"
			,$sql_filter
		)
	);
	
	$total=$db->Execute(
		$sql = sprintf("
			SELECT
				SUM(so.shipping) shipping
				,SUM(so.total - so.refunded) paid
			FROM
				shop_orders so
			WHERE
				so.id IN (
					SELECT DISTINCT
						shop_orders.id
					FROM
						shop_order_products
						,shop_products
						,shop_orders
					WHERE
						shop_order_products.product_id = shop_products.id
					AND
						shop_order_products.order_id = shop_orders.id
					AND
						%s
				)
		"
			,$sql_filter
		)
	);
	$total = $total->FetchRow();
?>