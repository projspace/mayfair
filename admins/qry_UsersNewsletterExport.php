<?
	$sql_where = array();
	$sql_where[] = 'shop_user_accounts.newsletter = 1';
	if(($keyword = trim($_GET['keyword'])) != '')
		$sql_where[] = sprintf("
		(
			shop_user_accounts.email LIKE %s
		OR
			shop_user_accounts.lastname LIKE %s
		OR
			shop_user_shops.name LIKE %s
		OR
			shop_user_orders.order_id = %u
		OR
			CONCAT(%s, shop_user_orders.order_id) = %s
		)
		"
			,$db->Quote('%'.$keyword.'%')
			,$db->Quote('%'.$keyword.'%')
			,$db->Quote('%'.$keyword.'%')
			,$keyword+0
			,$db->Quote($config['companyshort'])
			,$db->Quote($keyword)
		);
		
	if ( in_array ( $_GET['customer_type'], array ( 'student','confirmed_student','teacher','shop' ) ) ) 
		$sql_where[] = sprintf ( " shop_user_accounts.".$_GET['customer_type']." = 1 " );
	
		
	if($_GET['student']+0)
		$sql_where[] = sprintf("shop_user_accounts.student = 1");
	if($_GET['confirmed_student']+0)
		$sql_where[] = sprintf("shop_user_accounts.confirmed_student = 1");
		
	if(count($sql_where))
		$sql_where = implode(' AND ', $sql_where);
	else
		$sql_where = '1';

	$users=$db->Execute(
		$sql = sprintf("
			SELECT DISTINCT
				shop_user_accounts.*
			FROM
				shop_user_accounts
			LEFT JOIN
				shop_user_orders
			ON
				shop_user_orders.account_id = shop_user_accounts.id
			LEFT JOIN
				shop_user_shops
			ON
				shop_user_shops.user_id = shop_user_accounts.id
			WHERE
				%s
			GROUP BY
				shop_user_accounts.id
			ORDER BY
				email ASC
		"
			,$sql_where
		)
	);
?>