<?
	$sql_where = array();
	$sql_where[] = sprintf(" ( shop_products.name LIKE %s OR shop_products.code = %s OR shop_product_options.upc_code = %s OR shop_product_options.ean_code = %s ) ", $db->Quote('%'.trim($_GET['keyword']).'%'), $db->Quote(trim($_GET['keyword'])), $db->Quote(trim($_GET['keyword'])), $db->Quote(trim($_GET['keyword'])));
	if(count($sql_where))
		$sql_where = implode(' AND ', $sql_where);
	else
		$sql_where = '1';
	

	$products=$db->Execute(
		sprintf("
			SELECT
				shop_products.id
				,shop_products.name
				,shop_products.code
				,shop_products.category_id
			FROM
				shop_products
            LEFT JOIN
                shop_product_options
            ON
                shop_product_options.product_id = shop_products.id
			WHERE
				shop_products.id>1
			AND
				%s
            GROUP BY
                shop_products.id
			ORDER BY
				shop_products.name ASC
		"
			,$sql_where
		)
	);
?>
