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
	
	if($date_from == 0)
		$date_from = strtotime('2011-01-01');
	if($date_to == 0)
		$date_to = time();
		
	$sql_where = array();
	if($date_from)
		$sql_where[] = sprintf('shop_orders.`time` >= %u', $date_from);
	if($date_to)
		$sql_where[] = sprintf('shop_orders.`time` <= %u', $date_to);
	if(count($sql_where))
		$sql_where = implode(' AND ', $sql_where);
	else
		$sql_where = '1';
	
	switch(safe($_REQUEST['src']))
	{
		case 'bsv':
			$type = 'sellers';
			$title = 'Best Sellers by Value';
			$sort = array('field' => 'value', 'dir' => 'DESC');
			break;
		case 'wsq':
			$type = 'sellers';
			$title = 'Worst Sellers by Quantity';
			$sort = array('field' => 'count', 'dir' => 'ASC');
			break;
		case 'wsv':
			$type = 'sellers';
			$title = 'Worst Sellers by Value';
			$sort = array('field' => 'value', 'dir' => 'ASC');
			break;
		case 'bc':
			$type = 'customers';
			$title = 'Best Customers';
			$sort = array('field' => 'value', 'dir' => 'DESC');
			break;
		case 'wc':
			$type = 'customers';
			$title = 'Worst Customers';
			$sort = array('field' => 'value', 'dir' => 'ASC');
			break;
		case 'idle':
			$type = 'idle';
			$title = 'Idle Customers for over a year';
			$sort = array('field' => 'value', 'dir' => 'DESC');
			break;
		case 'bsq':
		default:
			$type = 'sellers';
			$title = 'Best Sellers by Quantity';
			$sort = array('field' => 'count', 'dir' => 'DESC');
			break;
	}
	
	if($type == 'customers')
		$results = $db->Execute(
			$sql = sprintf("
				SELECT
					shop_orders.email
					,SUM(shop_orders.paid) value
				FROM
					shop_orders
				WHERE
					%s
				GROUP BY
					shop_orders.email
				ORDER BY
					%s %s
			"
				,$sql_where
				,$sort['field']
				,$sort['dir']
			)
		);
	elseif($type == 'idle')
		$results = $db->Execute(
			$sql = sprintf("
				SELECT
					shop_orders.email
					,SUM(shop_orders.paid) value
				FROM
					shop_orders
				WHERE
					1
				GROUP BY
					shop_orders.email
				HAVING
					FROM_UNIXTIME(MAX(shop_orders.`time`)) < DATE_SUB(NOW(), INTERVAL 1 YEAR)
				ORDER BY
					%s %s
			"
				,$sort['field']
				,$sort['dir']
			)
		);
	else
		$results = $db->Execute(
			$sql = sprintf("
				SELECT
					shop_products.id
					,shop_products.name
					,shop_products.price
					,shop_products.category_id
					,SUM(shop_order_products.quantity) count
					,SUM(shop_order_products.quantity*shop_order_products.price) value
				FROM
					shop_products
					,shop_order_products
					,shop_orders
				WHERE
					shop_products.id = shop_order_products.product_id
				AND
					shop_order_products.order_id = shop_orders.id
				AND
					%s
				GROUP BY
					shop_products.id
				ORDER BY
					%s %s
			"
				,$sql_where
				,$sort['field']
				,$sort['dir']
			)
		);
?>