<?
	$sql_where = array();
	if($_REQUEST['approved_students']+0)
		$sql_where[] = sprintf("confirmed_student = 1");
		
	if(count($sql_where))
		$sql_where = implode(' AND ', $sql_where);
	else
		$sql_where = '1';
	
	$accounts = $db->Execute(
		$sql = sprintf("
			SELECT
				*
			FROM
				shop_user_accounts
			WHERE
				%s
			ORDER BY
				email ASC
		"
			,$sql_where
		)
	);
?>
