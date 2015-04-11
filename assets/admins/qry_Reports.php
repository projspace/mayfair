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
				SUM(paid) total
			FROM
				shop_orders
			WHERE
				%s
		"
			,$sql_where
		)
	);
	$sales = $sales->FetchRow();
	
	//bestsellers & most viewed & countries
	switch(safe($_REQUEST['sort']))
	{
		case 'name':
			$sort_field = 'name';
			break;
		case 'price':
			$sort_field = 'price';
			break;
		case 'value':
			$sort_field = 'value';
			break;
		case 'email':
			$sort_field = 'email';
			break;
		case 'count':
		default:
			$sort_field = 'count';
			break;
	}
	
	switch(safe($_REQUEST['sort_table']))
	{
		case 'best_sellers_value':
			$sort_table = 'best_sellers_value';
			break;
		case 'worst_sellers_quantity':
			$sort_table = 'worst_sellers_quantity';
			break;
		case 'worst_sellers_value':
			$sort_table = 'worst_sellers_value';
			break;
		case 'best_customers':
			$sort_table = 'best_customers';
			break;
		case 'worst_customers':
			$sort_table = 'worst_customers';
			break;
		case 'idle_customers':
			$sort_table = 'idle_customers';
			break;
		case 'best_sellers_quantity':
		default:
			$sort_table = 'best_sellers_quantity';
			break;
	}
	
	if(strtolower(trim($_REQUEST['sort_dir'])) == 'asc')
		$sort_dir = 'asc';
	else
		$sort_dir = 'desc';
		
	$sort = array();
	if($sort_table == 'best_sellers_quantity')
		$sort['best_sellers_quantity'] = array('field' => $sort_field, 'dir' => $sort_dir);
	else
		$sort['best_sellers_quantity'] = array('field' => 'count', 'dir' => 'DESC');
		
	if($sort_table == 'best_sellers_value')
		$sort['best_sellers_value'] = array('field' => $sort_field, 'dir' => $sort_dir);
	else
		$sort['best_sellers_value'] = array('field' => 'value', 'dir' => 'DESC');
		
	if($sort_table == 'worst_sellers_quantity')
		$sort['worst_sellers_quantity'] = array('field' => $sort_field, 'dir' => $sort_dir);
	else
		$sort['worst_sellers_quantity'] = array('field' => 'count', 'dir' => 'ASC');
		
	if($sort_table == 'worst_sellers_value')
		$sort['worst_sellers_value'] = array('field' => $sort_field, 'dir' => $sort_dir);
	else
		$sort['worst_sellers_value'] = array('field' => 'value', 'dir' => 'ASC');
		
	if($sort_table == 'best_customers')
		$sort['best_customers'] = array('field' => $sort_field, 'dir' => $sort_dir);
	else
		$sort['best_customers'] = array('field' => 'value', 'dir' => 'DESC');
		
	if($sort_table == 'worst_customers')
		$sort['worst_customers'] = array('field' => $sort_field, 'dir' => $sort_dir);
	else
		$sort['worst_customers'] = array('field' => 'value', 'dir' => 'ASC');
		
	if($sort_table == 'idle_customers')
		$sort['idle_customers'] = array('field' => $sort_field, 'dir' => $sort_dir);
	else
		$sort['idle_customers'] = array('field' => 'value', 'dir' => 'DESC');
		
	// Bestsellers
	// Bestsellers by Quantity
	$db->Execute(
		$sql = sprintf("
			CREATE TEMPORARY TABLE 
				temp_best_sellers_quantity
			SELECT
				shop_products.id
				,shop_products.name
				,shop_products.price
				,shop_products.category_id
				,SUM(shop_order_products.quantity) count
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
				count DESC
			LIMIT 10
		"
			,$sql_where
		)
	);
	$db->Execute(
		$sql = sprintf("
			ALTER TABLE temp_best_sellers_quantity ADD INDEX i_count(count)
		"
		)
	);
	$db->Execute(
		$sql = sprintf("
			ALTER TABLE temp_best_sellers_quantity ADD INDEX i_name(name)
		"
		)
	);
	$db->Execute(
		$sql = sprintf("
			ALTER TABLE temp_best_sellers_quantity ADD INDEX i_price(price)
		"
		)
	);
	$best_sellers_quantity=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				temp_best_sellers_quantity
			ORDER BY
				%s %s
		"
			,$sort['best_sellers_quantity']['field']
			,$sort['best_sellers_quantity']['dir']
		)
	);
	
	// Bestsellers by Value
	$db->Execute(
		$sql = sprintf("
			CREATE TEMPORARY TABLE 
				temp_best_sellers_value
			SELECT
				shop_products.id
				,shop_products.name
				,shop_products.price
				,shop_products.category_id
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
				value DESC
			LIMIT 10
		"
			,$sql_where
		)
	);
	$db->Execute(
		$sql = sprintf("
			ALTER TABLE temp_best_sellers_value ADD INDEX i_value(value)
		"
		)
	);
	$db->Execute(
		$sql = sprintf("
			ALTER TABLE temp_best_sellers_value ADD INDEX i_name(name)
		"
		)
	);
	$db->Execute(
		$sql = sprintf("
			ALTER TABLE temp_best_sellers_value ADD INDEX i_price(price)
		"
		)
	);
	$best_sellers_value=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				temp_best_sellers_value
			ORDER BY
				%s %s
		"
			,$sort['best_sellers_value']['field']
			,$sort['best_sellers_value']['dir']
		)
	);
	

	// Worst sellers
	// Worst sellers by Quantity
	$db->Execute(
		$sql = sprintf("
			CREATE TEMPORARY TABLE 
				temp_worst_sellers_quantity
			SELECT
				shop_products.id
				,shop_products.name
				,shop_products.price
				,shop_products.category_id
				,SUM(shop_order_products.quantity) count
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
				count ASC
			LIMIT 10
		"
			,$sql_where
		)
	);
	$db->Execute(
		$sql = sprintf("
			ALTER TABLE temp_worst_sellers_quantity ADD INDEX i_count(count)
		"
		)
	);
	$db->Execute(
		$sql = sprintf("
			ALTER TABLE temp_worst_sellers_quantity ADD INDEX i_name(name)
		"
		)
	);
	$db->Execute(
		$sql = sprintf("
			ALTER TABLE temp_worst_sellers_quantity ADD INDEX i_price(price)
		"
		)
	);
	$worst_sellers_quantity=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				temp_worst_sellers_quantity
			ORDER BY
				%s %s
		"
			,$sort['worst_sellers_quantity']['field']
			,$sort['worst_sellers_quantity']['dir']
		)
	);
	
	// Worst sellers by Value
	$db->Execute(
		$sql = sprintf("
			CREATE TEMPORARY TABLE 
				temp_worst_sellers_value
			SELECT
				shop_products.id
				,shop_products.name
				,shop_products.price
				,shop_products.category_id
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
				value ASC
			LIMIT 10
		"
			,$sql_where
		)
	);
	$db->Execute(
		$sql = sprintf("
			ALTER TABLE temp_worst_sellers_value ADD INDEX i_value(value)
		"
		)
	);
	$db->Execute(
		$sql = sprintf("
			ALTER TABLE temp_worst_sellers_value ADD INDEX i_name(name)
		"
		)
	);
	$db->Execute(
		$sql = sprintf("
			ALTER TABLE temp_worst_sellers_value ADD INDEX i_price(price)
		"
		)
	);
	$worst_sellers_value=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				temp_worst_sellers_value
			ORDER BY
				%s %s
		"
			,$sort['worst_sellers_value']['field']
			,$sort['worst_sellers_value']['dir']
		)
	);
	
	
	// Customers
	// Best customers
	$db->Execute(
		$sql = sprintf("
			CREATE TEMPORARY TABLE 
				temp_best_customers
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
				value DESC
			LIMIT 10
		"
			,$sql_where
		)
	);
	$db->Execute(
		$sql = sprintf("
			ALTER TABLE temp_best_customers ADD INDEX i_value(value)
		"
		)
	);
	$db->Execute(
		$sql = sprintf("
			ALTER TABLE temp_best_customers ADD INDEX i_email(email)
		"
		)
	);
	$best_customers=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				temp_best_customers
			ORDER BY
				%s %s
		"
			,$sort['best_customers']['field']
			,$sort['best_customers']['dir']
		)
	);
	
	// Worst customers
	$db->Execute(
		$sql = sprintf("
			CREATE TEMPORARY TABLE 
				temp_worst_customers
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
				value ASC
			LIMIT 10
		"
			,$sql_where
		)
	);
	$db->Execute(
		$sql = sprintf("
			ALTER TABLE temp_worst_customers ADD INDEX i_value(value)
		"
		)
	);
	$db->Execute(
		$sql = sprintf("
			ALTER TABLE temp_worst_customers ADD INDEX i_email(email)
		"
		)
	);
	$worst_customers=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				temp_worst_customers
			ORDER BY
				%s %s
		"
			,$sort['worst_customers']['field']
			,$sort['worst_customers']['dir']
		)
	);
	
	// Idle customers
	$db->Execute(
		$sql = sprintf("
			CREATE TEMPORARY TABLE 
				temp_idle_customers
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
				value DESC
			LIMIT 10
		"
		)
	);
	$db->Execute(
		$sql = sprintf("
			ALTER TABLE temp_idle_customers ADD INDEX i_value(value)
		"
		)
	);
	$db->Execute(
		$sql = sprintf("
			ALTER TABLE temp_idle_customers ADD INDEX i_email(email)
		"
		)
	);
	$idle_customers=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				temp_idle_customers
			ORDER BY
				%s %s
		"
			,$sort['idle_customers']['field']
			,$sort['idle_customers']['dir']
		)
	);
?>