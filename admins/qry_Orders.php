<?
	/**
	 * e-Commerce System
	 * Copyright (c) 2002-2006 Philip John, All Rights Reserved.
	 * Author	: Philip John
	 * Version	: 6.0
	 *
	 * PROPRIETARY/CONFIDENTIAL.  Use is subject to license terms.
	 */
?>
<?
	$sql_filter = array();
	
	$sql_filter[] = sprintf("shop_orders.processed = 0");
	// custom filters
	
	if(count($sql_filter))
		$sql_filter = implode(' AND ', $sql_filter);
	else
		$sql_filter = '1';
		
	$page = $_REQUEST['page']+0;
	if($page <= 0)
		$page = 1;
	$items_per_page = 20;
	
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
	
	$min_time=$db->Execute(
		sprintf("
			SELECT
				MIN(time) date
			FROM
				shop_orders
			WHERE
				%s
		"
			,$sql_filter
		)
	);
	$min_time = $min_time->FetchRow();
	$min_time = $min_time['date'];
	
	$max_time=$db->Execute(
		sprintf("
			SELECT
				MAX(time) date
			FROM
				shop_orders
			WHERE
				%s
		"
			,$sql_filter
		)
	);
	$max_time = $max_time->FetchRow();
	$max_time = $max_time['date'];
?>