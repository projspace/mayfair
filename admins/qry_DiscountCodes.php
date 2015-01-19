<?
	$sql_where = array();
	$sql_where[] = sprintf("shop_promotional_codes.deleted = 0");
	if(($keyword = trim($_GET['keyword'])) != '')
		$sql_where[] = sprintf(" ( shop_user_accounts.email LIKE %s OR CONCAT_WS(' ', shop_user_accounts.firstname, shop_user_accounts.lastname) = %s ) ", $db->Quote('%'.$keyword.'%'), $db->Quote('%'.$keyword.'%'));
	if(count($sql_where))
		$sql_where = implode(' AND ', $sql_where);
	else
		$sql_where = '1';
		
	$page = $_REQUEST['page']+0;
	if($page <= 0)
		$page = 1;
	$items_per_page = 10;
	
	$item_count = $db->Execute("SELECT COUNT(*) count FROM shop_promotional_codes WHERE ".$sql_where);
	$item_count = $item_count->fields['count'];

	switch(strtolower(trim($_REQUEST['sort'])))
	{
		case 'value':
			$sort = 'shop_promotional_codes.value';
			break;
		case 'used':
			$sort = 'shop_promotional_codes.used';
			break;
		default:
			$sort = 'shop_promotional_codes.expiry_date';
			break;
	}
	
	$sort_dir = (strtoupper(trim($_REQUEST['dir'])) == 'DESC')?'DESC':'ASC';
	
	$discount_codes = $db->Execute(
		$sql = sprintf("
			SELECT
				shop_promotional_codes.*
				,(SELECT COUNT(*) FROM shop_user_promotional_codes WHERE code_id = shop_promotional_codes.id AND shop_user_promotional_codes.order_id = 0 ) assigned
				,(SELECT COUNT(*) FROM shop_user_promotional_codes WHERE code_id = shop_promotional_codes.id AND shop_user_promotional_codes.order_id != 0 ) used
				,shop_user_accounts.firstname
				,shop_user_accounts.lastname
			FROM
				shop_promotional_codes
			LEFT JOIN
				shop_user_accounts
			ON
				shop_user_accounts.id = shop_promotional_codes.shop_account_id
			OR
				shop_user_accounts.id = shop_promotional_codes.teacher_account_id
			WHERE
				%s
			ORDER BY
				%s %s
			LIMIT %u, %u
		"
			,$sql_where
			,$sort
			,$sort_dir
			,($page-1)*$items_per_page
			,$items_per_page
		)
	);
?>
