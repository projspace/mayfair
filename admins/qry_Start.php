<?
	// sidebar
	$new_orders=$db->Execute(
		sprintf("
			SELECT
				COUNT(*) count
			FROM
				shop_orders
			WHERE
				processed = 0
		"
		)
	);
	$new_orders = $new_orders->FetchRow();
	$new_orders = $new_orders['count'];
	
	$sales=$db->Execute(
		sprintf("
			SELECT
				SUM(paid) total
				,AVG(paid) average
			FROM
				shop_orders
		"
		)
	);
	$sales = $sales->FetchRow();
	
	$items=$db->Execute(
		sprintf("
			SELECT
				SUM(shop_order_products.quantity) total
				,AVG(shop_order_products.quantity) average
			FROM
				shop_orders
				,shop_order_products
			WHERE
				shop_orders.id = shop_order_products.order_id
		"
		)
	);
	$items = $items->FetchRow();
	
	$last_order=$db->Execute(
		sprintf("
			SELECT
				shop_orders.id
				,shop_orders.paid
				,COUNT(shop_order_products.id) count
			FROM
				shop_orders
				,shop_order_products
			WHERE
				shop_orders.id = shop_order_products.order_id
			GROUP BY
				shop_orders.id
			ORDER BY
				shop_orders.time DESC
			LIMIT 1
		"
		)
	);
	$last_order = $last_order->FetchRow();
	
	//bestsellers & most viewed & countries
	switch(safe($_REQUEST['sort']))
	{
		case 'name':
			$sort_field = 'name';
			break;
		case 'price':
			$sort_field = 'price';
			break;
		case 'count':
		default:
			$sort_field = 'count';
			break;
	}
	
	switch(safe($_REQUEST['sort_table']))
	{
		case 'most_viewed':
			$sort_table = 'most_viewed';
			break;
		case 'countries':
			$sort_table = 'countries';
			break;
		case 'bestsellers':
		default:
			$sort_table = 'bestsellers';
			break;
	}
	
	if(strtolower(trim($_REQUEST['sort_dir'])) == 'asc')
		$sort_dir = 'asc';
	else
		$sort_dir = 'desc';
		
	$sort = array();
	if($sort_table == 'bestsellers')
		$sort['bestsellers'] = array('field' => $sort_field, 'dir' => $sort_dir);
	else
		$sort['bestsellers'] = array('field' => 'count', 'dir' => 'DESC');
		
	if($sort_table == 'most_viewed')
		$sort['most_viewed'] = array('field' => $sort_field, 'dir' => $sort_dir);
	else
		$sort['most_viewed'] = array('field' => 'count', 'dir' => 'DESC');
		
	if($sort_table == 'countries')
		$sort['countries'] = array('field' => $sort_field, 'dir' => $sort_dir);
	else
		$sort['countries'] = array('field' => 'count', 'dir' => 'DESC');
		
	// bestsellers
	$db->Execute(
		$sql = sprintf("
			CREATE TEMPORARY TABLE 
				temp_bestsellers
			SELECT
				shop_products.id
				,shop_products.name
				,shop_products.price
				,shop_products.category_id
				,SUM(shop_order_products.quantity) count
			FROM
				shop_products
				,shop_order_products
			WHERE
				shop_products.id = shop_order_products.product_id
			GROUP BY
				shop_products.id
			ORDER BY
				count DESC
			LIMIT 10
		"
		)
	);
	$db->Execute(
		$sql = sprintf("
			ALTER TABLE temp_bestsellers ADD INDEX i_count(count)
		"
		)
	);
	$db->Execute(
		$sql = sprintf("
			ALTER TABLE temp_bestsellers ADD INDEX i_name(name)
		"
		)
	);
	$db->Execute(
		$sql = sprintf("
			ALTER TABLE temp_bestsellers ADD INDEX i_price(price)
		"
		)
	);
	$bestsellers=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				temp_bestsellers
			ORDER BY
				%s %s
		"
			,$sort['bestsellers']['field']
			,$sort['bestsellers']['dir']
		)
	);
	
	
	//most viewed
	$db->Execute(
		$sql = sprintf("
			CREATE TEMPORARY TABLE 
				temp_most_viewed
			SELECT
				shop_products.id
				,shop_products.name
				,shop_products.price
				,shop_products.category_id
				,COUNT(shop_recent_products.product_id) count
			FROM
				shop_products
				,shop_recent_products
			WHERE
				shop_products.id = shop_recent_products.product_id
			AND
				shop_products.parent_id = 0
			GROUP BY
				shop_products.id
			ORDER BY
				count DESC
			LIMIT 10
		"
		)
	);
	$db->Execute(
		$sql = sprintf("
			ALTER TABLE temp_most_viewed ADD INDEX i_count(count)
		"
		)
	);
	$db->Execute(
		$sql = sprintf("
			ALTER TABLE temp_most_viewed ADD INDEX i_name(name)
		"
		)
	);
	$db->Execute(
		$sql = sprintf("
			ALTER TABLE temp_most_viewed ADD INDEX i_price(price)
		"
		)
	);
	$most_viewed=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				temp_most_viewed
			ORDER BY
				%s %s
		"
			,$sort['most_viewed']['field']
			,$sort['most_viewed']['dir']
		)
	);
	
	$countries=$db->Execute(
		sprintf("
			SELECT
				shop_orders.delivery_country name
				,SUM(shop_order_products.quantity * shop_order_products.price) price
				,SUM(shop_order_products.quantity) count
			FROM
				shop_orders
				,shop_order_products
			WHERE
				shop_orders.id = shop_order_products.order_id
			GROUP BY
				shop_orders.delivery_country
			ORDER BY
				%s %s
		"
			,$sort['countries']['field']
			,$sort['countries']['dir']
		)
	);
	
	// orders, ammounts & new registrations
	
	if(safe($_REQUEST['display']) == 'abandoned')
		$display = 'abandoned';
	else
		$display = 'orders';
		
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
				$date_from = strtotime(date('Y-m-d'))-6*86400;
				$date_to = strtotime(date('Y-m-d'));
				break;
			case '7_days':
			default:
				$_REQUEST['date']['custom'] = $_GET['date']['custom'] = '30_days';
				$date_from = strtotime(date('Y-m-d'))-29*86400;
				$date_to = strtotime(date('Y-m-d'));
				break;
		}
		$date_to += 86400 - 1;
	}
		
	$date_from += 0;
	$date_to += 0;
	
	if($date_from == 0)
		$date_from = strtotime('2011-08-01');
	if($date_to == 0)
		$date_to = time();
	
		
	if($display == 'abandoned')
	{
		$sql_where = array();
		$sql_where[] = sprintf('shop_sessions.nitems != 0');
		if($date_from)
			$sql_where[] = sprintf('shop_sessions.`lastaccess` >= %u', $date_from);
		if($date_to)
			$sql_where[] = sprintf('shop_sessions.`lastaccess` <= %u', $date_to);
		if(count($sql_where))
			$sql_where = implode(' AND ', $sql_where);
		else
			$sql_where = '1';
			
		$orders=$db->Execute(
			$sql = sprintf("
				SELECT
					DATE(FROM_UNIXTIME(shop_sessions.`lastaccess`)) date
					,COUNT(shop_sessions.id) count
				FROM
					shop_sessions
				WHERE
					%s
				GROUP BY
					date
				ORDER BY
					date ASC
			"
				,$sql_where
			)
		);
		
		$amounts=$db->Execute(
			sprintf("
				SELECT
					DATE(FROM_UNIXTIME(shop_sessions.`lastaccess`)) date
					,SUM(shop_sessions.total) amount
				FROM
					shop_sessions
				WHERE
					%s
				GROUP BY
					date
				ORDER BY
					date ASC
			"
				,$sql_where
			)
		);
	}
	else
	{
		$sql_where = array();
		if($date_from)
			$sql_where[] = sprintf('shop_orders.`time` >= %u', $date_from);
		if($date_to)
			$sql_where[] = sprintf('shop_orders.`time` <= %u', $date_to);
		if(count($sql_where))
			$sql_where = implode(' AND ', $sql_where);
		else
			$sql_where = '1';
			
		$orders=$db->Execute(
			$sql = sprintf("
				SELECT
					DATE(FROM_UNIXTIME(shop_orders.`time`)) date
					,COUNT(shop_orders.id) count
				FROM
					shop_orders
				WHERE
					%s
				GROUP BY
					date
				ORDER BY
					date ASC
			"
				,$sql_where
			)
		);
		
		$amounts=$db->Execute(
			sprintf("
				SELECT
					DATE(FROM_UNIXTIME(shop_orders.`time`)) date
					,SUM(shop_orders.paid) amount
				FROM
					shop_orders
				WHERE
					%s
				GROUP BY
					date
				ORDER BY
					date ASC
			"
				,$sql_where
			)
		);
	}
	$sql_where = array();
	if($date_from)
		$sql_where[] = sprintf('shop_user_accounts.`created` >= %s', $db->Quote(date('Y-m-d H:i:s',$date_from)));
	if($date_to)
		$sql_where[] = sprintf('shop_user_accounts.`created` <= %s', $db->Quote(date('Y-m-d H:i:s', $date_to)));
	if(count($sql_where))
		$sql_where = implode(' AND ', $sql_where);
	else
		$sql_where = '1';
	$registrations=$db->Execute(
		sprintf("
			SELECT
				DATE(shop_user_accounts.`created`) date
				,COUNT(shop_user_accounts.id) count
			FROM
				shop_user_accounts
			WHERE
				%s
			GROUP BY
				date
			ORDER BY
				date ASC
		"
			,$sql_where
		)
	);
?>