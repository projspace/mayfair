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
		
	//bestsellers & most viewed & countries
	switch(safe($_REQUEST['sort']))
	{
		case 'total':
			$sort_field = 'total';
			break;
		case 'customer':
		default:
			$sort_field = 'customer';
			break;
	}
	
	if(strtolower(trim($_REQUEST['sort_dir'])) == 'asc')
		$sort_dir = 'asc';
	else
		$sort_dir = 'desc';
		
	$reports = $db->Execute(
		$sql = sprintf("
			SELECT
				shop_user_accounts.id
				,shop_user_accounts.email customer
				,shop_user_accounts.firstname
				,shop_user_accounts.lastname
				,SUM(susc.amount) total
			FROM
				shop_user_accounts
				,shop_user_shops
				,shop_user_shop_commissions susc
				,shop_orders
			WHERE
				shop_user_accounts.id = shop_user_shops.user_id
			AND
				shop_user_shops.hidden = 0
			AND
				shop_user_shops.id = susc.shop_id
			AND
				susc.order_id = shop_orders.id
			AND
				%s
			GROUP BY
				shop_user_accounts.id
			ORDER BY
				%s %s
		"
			,$sql_where
			,$sort_field
			,$sort_dir
		)
	);
	$report_orders = $db->Execute(
		$sql = sprintf("
			SELECT
				shop_orders.*
				,shop_user_accounts.id account_id
				,susc.amount commission
			FROM
				shop_user_accounts
				,shop_user_shops
				,shop_user_shop_commissions susc
				,shop_orders
			WHERE
				shop_user_accounts.id = shop_user_shops.user_id
			AND
				shop_user_shops.hidden = 0
			AND
				shop_user_shops.id = susc.shop_id
			AND
				susc.order_id = shop_orders.id
			AND
				%s
			ORDER BY
				shop_orders.time ASC
		"
			,$sql_where
		)
	);
	$report_orders = $report_orders->GetRows();
?>