<?
	$category=$db->Execute(
		sprintf("
			SELECT
				shop_categories.*
			FROM
				shop_categories
			LEFT JOIN
				shop_category_restrictions
			ON
				shop_category_restrictions.category_id = shop_categories.id
			AND
				shop_category_restrictions.area_id = %u
			WHERE
				shop_categories.id=%u
			AND
				shop_category_restrictions.id IS NULL
		"
			,$session->session->fields['area_id']
			,$category_id
		)
	);
	$category = $category->FetchRow();
	if(!$category)
	{
		header("location: ".$config['dir']);
		exit;
	}
	
	// meta tags
	if($category['meta_title']!="")
		$elems->meta['title']=$category['meta_title'];
	else
		$elems->meta['title']=$config['company']." - ".$category['name'];

	if($category['meta_description']!="")
		$elems->meta['description']=$category['meta_description'];
	else
		$elems->meta['description']=trim(strip_tags($category['content']));

	$elems->meta['keywords']=$category['meta_keywords'];
	
	// trail menu
	foreach(unserialize($category['trail']) as $index=>$row)
	{
		if($index < 2)
			continue;
		$trail['menu'][] = array('url'=>category_url($row['id']), 'name'=>$row['name']);
	}
	
	// trail submenu
	$results=$db->Execute(
		sprintf("
			SELECT DISTINCT
				shop_categories.id
				,shop_categories.name
			FROM
				shop_categories
			LEFT JOIN
				shop_category_restrictions
			ON
				shop_category_restrictions.category_id = shop_categories.id
			AND
				shop_category_restrictions.area_id = %u
			WHERE
				shop_categories.parent_id=%u
			AND
				shop_category_restrictions.id IS NULL
			ORDER BY
				%s ASC
		"
			,$session->session->fields['area_id']
			,$category_id
			,($category['childord']==1) ? "shop_categories.ord" : "shop_categories.name"
		)
	);
	$sub_categories = array();
	$tmp = array();
	while($row = $results->FetchRow())
	{
		$trail['submenu'][] = array('url'=>category_url($row['id']), 'name'=>$row['name']);
		$sub_categories[] = $row;
		$tmp[] = $row['id'];
	}
	
	$price_tmp = $tmp;
	
	if($_REQUEST['sub_category_id']+0)
	{
		$tmp = array($_REQUEST['sub_category_id']+0);
		$category_ids = $tmp;
	}
	else
	{
		$category_ids = $tmp;
		array_unshift($category_ids, $category_id);
	}

	while(count($tmp))
	{
		$results=$db->Execute(
			sprintf("
				SELECT
					shop_categories.id
				FROM
					shop_categories
				LEFT JOIN
					shop_category_restrictions
				ON
					shop_category_restrictions.category_id = shop_categories.id
				AND
					shop_category_restrictions.area_id = %u
				WHERE
					parent_id IN (%s)
				AND
					shop_category_restrictions.id IS NULL
			"
				,$session->session->fields['area_id']
				,implode(',',$tmp)
			)
		);
		$tmp = array();
		while($row = $results->FetchRow())
		{
			$tmp[] = $row['id'];
			$category_ids[] = $row['id'];
		}
	}
	
	$price_category_ids = $tmp;
	array_unshift($price_category_ids, $category_id);
	while(count($price_tmp))
	{
		$results=$db->Execute(
			sprintf("
				SELECT
					shop_categories.id
				FROM
					shop_categories
				LEFT JOIN
					shop_category_restrictions
				ON
					shop_category_restrictions.category_id = shop_categories.id
				AND
					shop_category_restrictions.area_id = %u
				WHERE
					parent_id IN (%s)
				AND
					shop_category_restrictions.id IS NULL
			"
				,$session->session->fields['area_id']
				,implode(',',$price_tmp)
			)
		);
		$price_tmp = array();
		while($row = $results->FetchRow())
		{
			$price_tmp[] = $row['id'];
			$price_category_ids[] = $row['id'];
		}
	}
	
	// products
	$page = $_REQUEST['page']+0;
	if($page <= 0)
		$page = 1;
	$items_per_page = 12;
	
	$sql_where = array();
	$sql_where['category_ids'] = sprintf("shop_products.category_id IN (%s)", implode(',',$category_ids));
	$sql_where[] = sprintf("shop_products.id > 1");
	if($_REQUEST['min_price']+0)
		$sql_where['min_price'] = sprintf("shop_products.price >= %f", $_REQUEST['min_price']);
	if($_REQUEST['max_price']+0)
		$sql_where['max_price'] = sprintf("shop_products.price <= %f", $_REQUEST['max_price']);
	if(count($_REQUEST['filter_ids']))
		$sql_where['filter_ids'] = sprintf("shop_product_filters.filter_id IN (%s)", implode(',', array_map(create_function('$a', 'return $a+0;'), $_REQUEST['filter_ids'])));
	
	
	$item_count=$db->Execute(
		$sql = sprintf("
			SELECT
				COUNT(DISTINCT shop_products.id) count
			FROM
				shop_products
			LEFT JOIN
				shop_product_restrictions
			ON
				shop_product_restrictions.product_id = shop_products.id
			AND
				shop_product_restrictions.area_id = %u
			LEFT JOIN
				shop_product_tags
			ON
				shop_product_tags.product_id = shop_products.id
			LEFT JOIN
				shop_product_filters
			ON
				shop_product_filters.product_id = shop_products.id
			WHERE
				shop_product_restrictions.id IS NULL
			AND
				%s
		"
			,$session->session->fields['area_id']
			,implode(' AND ',$sql_where)
		)
	);
	$item_count = $item_count->FetchRow();
	$item_count = $item_count['count'];
	
	
	switch(strtolower(trim($_REQUEST['sort'])))
	{
		case 'name':
			$sort_field = 'shop_products.name';
			if(strtolower(trim($_REQUEST['sort_dir'])) == 'desc')
				$sort_dir = 'desc';
			else
				$sort_dir = 'asc';
			break;
		case 'price':
			$sort_field = 'shop_products.price';
			if(strtolower(trim($_REQUEST['sort_dir'])) == 'desc')
				$sort_dir = 'desc';
			else
				$sort_dir = 'asc';
			break;
		case 'recent':
			$sort_field = 'shop_products.updated';
			if(strtolower(trim($_REQUEST['sort_dir'])) == 'desc')
				$sort_dir = 'desc';
			else
				$sort_dir = 'asc';
			break;
		default:
			switch($category['productord'])
			{
				default:
				case "0":
					$sort_field = 'shop_products.name';
					$sort_dir = 'asc';
					break;
				case "1":
					$sort_field = 'shop_products.name';
					$sort_dir = 'desc';
					break;
				case "2":
					$sort_field = 'shop_products.price';
					$sort_dir = 'asc';
					break;
				case "3":
					$sort_field = 'shop_products.price';
					$sort_dir = 'desc';
					break;
				case "4":
					$sort_field = 'shop_products.ord';
					$sort_dir = 'asc';
					break;
			}
			break;
	}
	
	$products=$db->Execute(
		$sql = sprintf("
			SELECT
				shop_products.*
				,GROUP_CONCAT(DISTINCT shop_meta_tags.name SEPARATOR ', ') tags
			FROM
				shop_products
			LEFT JOIN
				shop_product_restrictions
			ON
				shop_product_restrictions.product_id = shop_products.id
			AND
				shop_product_restrictions.area_id = %u
			LEFT JOIN
			(	
				shop_product_tags
				,shop_meta_tags
			)
			ON
				shop_product_tags.product_id = shop_products.id
			AND
				shop_product_tags.tag_id = shop_meta_tags.id
			LEFT JOIN
				shop_product_filters
			ON
				shop_product_filters.product_id = shop_products.id
			WHERE
				shop_product_restrictions.id IS NULL
			AND
				%s
			GROUP BY
				shop_products.id
			ORDER BY
				%s %s
			LIMIT
				%u, %u
		"
			,$session->session->fields['area_id']
			,implode(' AND ',$sql_where)
			,$sort_field
			,$sort_dir
			,($page - 1)*$items_per_page
			,$items_per_page
		)
	);
	
	// prices
	$sql_where_price = $sql_where;
	unset($sql_where_price['min_price']);
	unset($sql_where_price['max_price']);
	$sql_where_price['category_ids'] = sprintf("shop_products.category_id IN (%s)", implode(',',$price_category_ids));
	
	$lowest_price=$db->Execute(
		$sql = sprintf("
			SELECT
				MIN(shop_products.price) min
			FROM
				shop_products
			LEFT JOIN
				shop_product_restrictions
			ON
				shop_product_restrictions.product_id = shop_products.id
			AND
				shop_product_restrictions.area_id = %u
			LEFT JOIN
				shop_product_tags
			ON
				shop_product_tags.product_id = shop_products.id
			LEFT JOIN
				shop_product_filters
			ON
				shop_product_filters.product_id = shop_products.id
			WHERE
				shop_product_restrictions.id IS NULL
			AND
				%s
		"
			,$session->session->fields['area_id']
			,implode(' AND ',$sql_where_price)
		)
	);
	$lowest_price = $lowest_price->FetchRow();
	$lowest_price = floor($lowest_price['min']);
	
	$highest_price=$db->Execute(
		$sql = sprintf("
			SELECT
				MAX(shop_products.price) max
			FROM
				shop_products
			LEFT JOIN
				shop_product_restrictions
			ON
				shop_product_restrictions.product_id = shop_products.id
			AND
				shop_product_restrictions.area_id = %u
			LEFT JOIN
				shop_product_tags
			ON
				shop_product_tags.product_id = shop_products.id
			LEFT JOIN
				shop_product_filters
			ON
				shop_product_filters.product_id = shop_products.id
			WHERE
				shop_product_restrictions.id IS NULL
			AND
				%s
		"
			,$session->session->fields['area_id']
			,implode(' AND ',$sql_where_price)
		)
	);
	$highest_price = $highest_price->FetchRow();
	$highest_price = ceil($highest_price['max']);
	
	// filters
	$sql_where_filters = $sql_where;
	unset($sql_where_filters['filter_ids']);
	$results=$db->Execute(
		$sql = sprintf("
			SELECT DISTINCT
				shop_filters.*
			FROM
			(
				shop_products
				,shop_product_filters
				,shop_filters
			)
			LEFT JOIN
				shop_product_restrictions
			ON
				shop_product_restrictions.product_id = shop_products.id
			AND
				shop_product_restrictions.area_id = %u
			WHERE
				shop_products.id = shop_product_filters.product_id
			AND
				shop_filters.id = shop_product_filters.filter_id
			AND
				shop_product_restrictions.id IS NULL
			AND
				%s
			ORDER BY
				shop_filters.type ASC
				,shop_filters.name ASC
		"
			,$session->session->fields['area_id']
			,implode(' AND ',$sql_where_filters)
		)
	);
	$filters = array();
	while($row = $results->FetchRow())
		$filters[$row['type']][] = $row;
?>
