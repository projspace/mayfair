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
				
	$page = $_REQUEST['page']+0;
	if($page <= 0)
		$page = 1;
	$items_per_page = 10;
	
	$item_count=$db->Execute(
		sprintf("
			SELECT
				COUNT(*) count
			FROM
				shop_orders 
			WHERE
				%s
		"
			,$sql_filter
		)
	);
	$item_count = $item_count->FetchRow();
	$item_count = $item_count['count'];
		
	$orders=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				shop_orders
			WHERE
				%s
			ORDER BY
				time DESC
			LIMIT
				%u, %u
		"
			,$sql_filter
			,($page - 1)*$items_per_page
			,$items_per_page
		)
	);
?>