<?
	$categories=$db->Execute(
		sprintf("
			SELECT
				sc1.id
				,sc1.name
				,sc1.trail
				,COUNT(sc2.id) children
			FROM
				shop_categories sc1
			LEFT JOIN
				shop_categories sc2
			ON
				sc2.parent_id = sc1.id
			GROUP BY
				sc1.id
			HAVING
				COUNT(sc2.id) = 0
			ORDER BY
				sc1.lft ASC
		"
		)
	);
	
	$sql_where = array();
	$sql_where[] = sprintf("id > 1");
	if($_REQUEST['filter']['category_id']+0)
		$sql_where[] = sprintf("category_id = %u", $_REQUEST['filter']['category_id']);
	if(trim($_REQUEST['filter']['keyword']) != '')
		$sql_where[] = sprintf("name LIKE %s", $db->Quote('%'.safe($_REQUEST['filter']['keyword']).'%'));
		
	if(count($sql_where))
		$sql_where = implode(' AND ', $sql_where);
	else
		$sql_where = '1';
		
	$products=$db->Execute(
		sprintf("
			SELECT
				id
				,name
				,code
			FROM
				shop_products
			WHERE
				%s
		"
			,$sql_where
		)
	);
?>