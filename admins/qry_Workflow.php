<?
	$ret = preg_match('/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/i', trim($_REQUEST['start_date']), $matches);
	if($ret)
		$start_date = mktime(0, 0, 0, $matches[2], $matches[1], $matches[3]);
	else
		$start_date = 0;
	if($start_date <= 0)
	{
		error("Please enter a valid start date","Input Error");
		$ok = false;
		return;
	}	
	$ret = preg_match('/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/i', trim($_REQUEST['end_date']), $matches);
	if($ret)
		$end_date = mktime(23, 59, 59, $matches[2], $matches[1], $matches[3]);
	else
		$end_date = 0;
	if($end_date <= 0)
	{
		error("Please enter a valid end date","Input Error");
		$ok = false;
		return;
	}
	
	$sql_filter = array();
	
	if($_REQUEST['records']+0)
		$sql_filter[] = sprintf("shop_orders.processed > 0");
	else
		$sql_filter[] = sprintf("shop_orders.processed = 0");
	// custom filters
	$sql_filter[] = sprintf("shop_orders.`time` >= %u", $start_date);
	$sql_filter[] = sprintf("shop_orders.`time` <= %u", $end_date);
	
	if(count($sql_filter))
		$sql_filter = implode(' AND ', $sql_filter);
	else
		$sql_filter = '1';
	
	$orders=$db->Execute(
		$sql = sprintf("
			SELECT
				*
			FROM
				shop_orders
			WHERE
				%s
			ORDER BY
				time ASC
		"
			,$sql_filter
		)
	);
	$ok = true;
?>