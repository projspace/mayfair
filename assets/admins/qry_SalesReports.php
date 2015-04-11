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
		
	$sales=$db->Execute(
		sprintf("
			SELECT
				SUM((shop_order_products.price - shop_order_products.discount)*shop_order_products.quantity) total
			FROM
				shop_orders
				,shop_order_products
			WHERE
				shop_order_products.order_id = shop_orders.id
			AND
				%s
		"
			,$sql_where
		)
	);
	$sales = $sales->FetchRow();
	
	//bestsellers & most viewed & countries
	switch(safe($_REQUEST['sort']))
	{
		case 'code':
			$sort_field = 'code';
			break;
		case 'name':
			$sort_field = 'name';
			break;
		case 'price':
			$sort_field = 'price';
			break;
		case 'vat':
			$sort_field = 'vat';
			break;
		case 'total':
			$sort_field = 'total';
			break;
		case 'avg_discount':
			$sort_field = 'avg_discount';
			break;
		case 'total_discount':
			$sort_field = 'total_discount';
			break;
		case 'quantity':
		default:
			$sort_field = 'quantity';
			break;
	}
	
	if(strtolower(trim($_REQUEST['sort_dir'])) == 'asc')
		$sort_dir = 'asc';
	else
		$sort_dir = 'desc';
		
	$products = $db->Execute(
		$sql = sprintf("
			SELECT
				shop_products.id
				,shop_products.code
				,shop_products.name
				,shop_products.category_id
				,shop_products.short_description
				,AVG(ROUND(shop_order_products.price - shop_order_products.discount, 2)) price
				,SUM(shop_order_products.quantity) quantity
				,SUM((shop_order_products.price - shop_order_products.discount)*shop_order_products.quantity) total
				,AVG(shop_order_products.promotional_discount) avg_discount
				,SUM(shop_order_products.promotional_discount) total_discount
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
			,$sort_field
			,$sort_dir
		)
	);
?>