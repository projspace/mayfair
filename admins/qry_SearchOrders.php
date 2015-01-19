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
	$search_sql = array();
	if($_REQUEST['id']!="")
		$search_sql[] = sprintf("shop_orders.id = %u", $_REQUEST['id']);
	if($_REQUEST['date']!="")
	{
		$date=explode("/",$_REQUEST['date']);
		$time=mktime(0,0,0,$date[1],$date[0],$date[2]);
		$search_sql[] = sprintf("(shop_orders.time >= %u AND shop_orders.time<=%u)", $time, $time+86400);
	}
	if($_REQUEST['name']!="")
		$search_sql[] = sprintf("shop_orders.name LIKE '%%%s%%'", mysql_real_escape_string($_REQUEST['name']));
	if($_REQUEST['address']!="")
		$search_sql[] = sprintf("shop_orders.address LIKE '%%%s%%'", mysql_real_escape_string($_REQUEST['address']));
	if($_REQUEST['postcode']!="")
		$search_sql[] = sprintf("shop_orders.postcode LIKE '%%%s%%'", mysql_real_escape_string($_REQUEST['postcode']));
	if($_REQUEST['email']!="")
		$search_sql[] = sprintf("shop_orders.email LIKE '%%%s%%'", mysql_real_escape_string($_REQUEST['email']));
		
	if(count($search_sql))
		$search_sql = implode(' AND ', $search_sql);
	else
		$search_sql = '1';
		
	$orders=$db->Execute(
		$sql = sprintf("
			SELECT 
				* 
			FROM 
				shop_orders 
			WHERE
				%s
			ORDER BY 
				shop_orders.time ASC
		"
			,$search_sql
		)
	);
?>