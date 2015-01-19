<?
	$sql_where = array();
	if(($keyword = trim($_GET['keyword'])) != '')
		$sql_where[] = sprintf(" ( shop_products.name LIKE %s OR shop_products.code = %s ) ", $db->Quote('%'.$keyword.'%'), $db->Quote($keyword));
	if(count($sql_where))
		$sql_where = implode(' AND ', $sql_where);
	else
		$sql_where = '1';
		
	$refs=$db->Execute(
		sprintf("
			SELECT
				shop_refs.id
				,shop_products.id AS product_id
				,shop_products.name
				,shop_products.code
				,shop_products.description
				,shop_products.price
				,shop_products.stock
			FROM
				shop_refs
				,shop_products
			WHERE
				shop_refs.category_id=%u
			AND
				shop_refs.product_id=shop_products.id
			AND
				%s
			ORDER BY
				shop_products.name
		"
			,$_REQUEST['category_id']
			,$sql_where
		)
	);

	switch($category->fields['productord'])
	{
		default:
		case "price_desc":
			$ord="shop_products.price DESC";
			break;
		case "price_asc":
			$ord="shop_products.price ASC";
			break;
		case "newest":
			$ord="shop_products.added DESC";
			break;
		case "manual":
			$ord="shop_products.ord ASC";
			break;
	}

	$products=$db->Execute(
		sprintf("
			SELECT
				shop_products.id
				,shop_products.parent_id
				,shop_products.hidden
				,shop_products.name
				,shop_products.code
				,shop_products.description
				,shop_products.price
				,shop_products.ord
				,SUM(shop_product_options.quantity) stock
			FROM
				shop_products
			LEFT JOIN
				shop_product_options
			ON
				shop_product_options.product_id = shop_products.id
			WHERE
				shop_products.category_id=%u
			AND
				shop_products.id>1
			AND
				%s
			GROUP BY
				shop_products.id
			ORDER BY
				%s
		"
			,$_REQUEST['category_id']
			,$sql_where
			,$ord
		)
	);
?>
