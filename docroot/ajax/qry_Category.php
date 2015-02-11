<?
	include("../lib/cfg_Config.php");
	include("../lib/adodb/adodb.inc.php");
	include("../lib/act_OpenDB.php");
	include("../lib/lib_Common.php");
	//Elements------------
	include("../lib/lib_Elements.php");
	include("../lib/lib_CustomElements.php");
	$elems=new CustomElements($db,$none,$config,$none);
	//--------------------
	
	$result=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				shop_variables
			WHERE
				name = 'product_options'
		"
		)
	);
	$result = $result->FetchRow();
	if($result)
		define('PRODUCT_OPTIONS', $result['value']);
	else
		define('PRODUCT_OPTIONS', 'upc_only');
	
	$json = array();

	$top_parent=$db->Execute(
		sprintf("
			SELECT
				sc.id
				,sc.name
			FROM
				shop_categories sc
			LEFT JOIN
				shop_categories sc2
			ON
				sc.lft <= sc2.lft
			AND
				sc2.rgt <= sc.rgt
			WHERE
				sc.id > 1
			AND
				sc2.id = %u
			ORDER BY
				sc.lft ASC
			LIMIT 1
		"
			,$_REQUEST['category_id']
		)
	);
	$top_parent = $top_parent->FetchRow();
	
	$filter_item_ids = array();
	$price = array('min'=>null, 'max'=>null);
	if(isset($_REQUEST['filters']))
	{
		$category_id = null;
		foreach($_REQUEST['filters'] as $row)
			if($row['name'] == 'category_id')
			{
				$arr = (array)$row['value'];
				$category_id = array_shift($arr)+0;
			}
            elseif($row['name'] == 'brand_id')
			{
				$brand_ids = (array)$row['value'];
			}
			elseif($row['name'] == 'price')
			{
				$arr = (array)$row['value'];
				$price['min'] = array_shift($arr);
				$price['max'] = array_shift($arr);
			}
			elseif(strpos($row['name'], 'filter_') === 0)
			{
				foreach((array)$row['value'] as $filter_item_id)
					$filter_item_ids[$filter_item_id] = 1;
			}
		if(!$category_id)
			$category_id = $top_parent['id'];
	}
	else
    {
		$category_id = $_REQUEST['category_id']+0;
        $brand_ids = array();
        if($_REQUEST['brand_id']+0)
            $brand_ids[] = $_REQUEST['brand_id']+0;
    }

    if(!$category_id)
        $category_id = 1;
		
	$category=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				shop_categories
			WHERE
				id = %u
		"
			,$category_id
		)
	);
	$category = $category->FetchRow();
	
	$title = array($top_parent['name']);
	if($category['id'] != $top_parent['id'])
		$title[] = $category['name'];
	$json['title'] = implode(' / ', $title);
	
    $sql_where = array();
	$sql_where[] = sprintf("shop_products.id > 1");
	$sql_where[] = sprintf("shop_products.hidden = 0");
	$sql_where[] = sprintf("shop_products.parent_id = 0");
    if(count($brand_ids))
	    $sql_where['brand_id'] = sprintf("shop_products.brand_id IN (%s)", implode(',', $brand_ids));
	if(PRODUCT_OPTIONS == 'upc_only')
		$sql_where[] = sprintf("shop_product_options.upc_code IS NOT NULL");
	if(strtolower($_REQUEST['category_type']) == 'new')
		$sql_where[] = sprintf("DATEDIFF(CURDATE(), shop_products.added) <= 30");
	if(strtolower($_REQUEST['category_type']) == 'special')
		$sql_where[] = sprintf("shop_products.special = 1");
	if(count($filter_item_ids))
		$sql_where['filter_item_ids'] = sprintf("shop_product_filters.filter_id IN (%s)", implode(',', array_keys($filter_item_ids)));
	if($price['min'])
		$sql_where['price_min'] = sprintf("shop_products.price >= %u", $price['min']);
	if($price['max'])
		$sql_where['price_max'] = sprintf("shop_products.price <= %u", $price['max']);

	$sql_join = array();
	$sql_join_tables = array();
	$sql_join_where = array();
	if(count($filter_item_ids))
	{
		$sql = array();
		foreach($filter_item_ids as $filter_item_id=>$unused)
		{
			$sql_join[] = sprintf('LEFT JOIN shop_product_filters spf_%1$u ON spf_%1$u.product_id = shop_products.id AND spf_%1$u.filter_id = %1$u', $filter_item_id);
			$sql[] = sprintf('spf_%1$u.filter_id IS NOT NULL', $filter_item_id);
			$sql_join_tables[] = sprintf('shop_product_filters spf_%1$u', $filter_item_id);
			$sql_join_where[] = sprintf('(spf_%1$u.product_id = shop_products.id AND spf_%1$u.filter_id = %1$u)', $filter_item_id);
		}	
		
		$sql_where['filter_item_ids'] = implode(' AND ', $sql);
	}

    $sql_where[] = sprintf("(shop_products.category_id IN (%1\$s) OR shop_refs.category_id IN (%1\$s))", implode(',', $elems->qry_SubcategoryIDs($category['id'])));

    if(!count($results = $elems->qry_Categories($category['id'])))
	{
        $results=$db->Execute(
            sprintf("
                SELECT
                    sc.id
                    ,sc.name
                FROM
                    shop_categories sc
                LEFT JOIN
                    shop_categories sc2
                ON
                    sc.parent_id = sc2.id
                WHERE
                    sc.parent_id = %u
                ORDER BY
                    IF(sc2.childord, sc.ord, sc.name) ASC
            "
                ,$category['parent_id']
            )
        );
        $results = $results->GetRows();
    }
    $filter_categories = array();
    foreach($results as $row)
    {
        $option = array(
            'value'		=>	$row['id']
            ,'display'	=>	$row['name']
        );
        if($row['id'] == $category_id)
            $option['on'] = true;

        $filter_categories[] = $option;
    }

    // brands
    $sql_temp  = $sql_where;
    unset($sql_temp['brand_id']);
    unset($sql_temp['price_min']);
    unset($sql_temp['price_max']);
    unset($sql_temp['filter_item_ids']);
    $results=$db->Execute(
        sprintf("
            SELECT
                shop_brands.id
                ,shop_brands.name
            FROM
                shop_brands
            JOIN
                shop_products
            ON
                shop_products.brand_id = shop_brands.id
            JOIN
                shop_product_options
            ON
                shop_product_options.product_id = shop_products.id
            LEFT JOIN
			    shop_refs
            ON
                shop_refs.product_id = shop_products.id
            WHERE
                %s
            GROUP BY
                shop_brands.id
        "
            ,implode(' AND ', $sql_temp)
        )
    );
    $filter_brands = array();
    while($row = $results->FetchRow())
    {
        $option = array(
            'value'		=>	$row['id']
            ,'display'	=>	$row['name']
        );
        if(in_array($row['id'], $brand_ids))
            $option['on'] = true;

        $filter_brands[] = $option;
    }

    // category filters
    $sql_temp  = $sql_where;
    unset($sql_temp['filter_item_ids']);
    foreach($sql_join_where as $sql)
        $sql_temp[] = $sql;

    $db->Execute(
		$sql = sprintf("
			CREATE TEMPORARY TABLE
				temp_prod
			SELECT
				shop_products.id product_id
			FROM
				shop_products
			LEFT JOIN
				shop_product_options
			ON
				shop_product_options.product_id = shop_products.id
            LEFT JOIN
			    shop_refs
            ON
                shop_refs.product_id = shop_products.id
			WHERE
				%s
			GROUP BY
				shop_products.id
		"
			,implode(' AND ', $sql_temp)
		)
	);
	$db->Execute($sql = sprintf("ALTER TABLE temp_prod ADD INDEX i_product_id(product_id);"));

    $results=$db->Execute(
        $sql=sprintf("
            SELECT
                shop_category_filter_items.*
                ,shop_category_filters.id filter_id
                ,shop_category_filters.name filter_name
                ,shop_category_filters.type
                ,COUNT(DISTINCT spf2.product_id) count
            FROM
                shop_category_filters
            LEFT JOIN
                shop_category_filter_items
            ON
                shop_category_filters.id = shop_category_filter_items.filter_id
            LEFT JOIN
            (
                shop_product_filters spf2
                ,temp_prod
            )
            ON
                spf2.filter_id = shop_category_filter_items.id
            AND
                temp_prod.product_id = spf2.product_id
            WHERE
                shop_category_filters.category_id = %u
            GROUP BY
                shop_category_filters.id
                ,shop_category_filter_items.id
            ORDER BY
                shop_category_filters.ord ASC
                ,shop_category_filter_items.ord ASC
        "
            ,count($sql_join_tables)?','.implode(',', $sql_join_tables):''
            ,$category['id']
        )
    );

    $filters = array();
    while($row = $results->FetchRow())
    {
        if(!isset($filters[$row['filter_id']]))
            $filters[$row['filter_id']] = array('id'=>$row['filter_id'], 'name'=>$row['filter_name'], 'type'=>$row['type'], 'items'=>array());
        if($row['id'])
            $filters[$row['filter_id']]['items'][] = $row;
    }

    $json['filters'] = array();
    $json['filters'][] = array('name'=>'category_id', 'display' => 'Category', 'type' => 'single', 'options'=> $filter_categories);
    if(count($filter_brands))
        $json['filters'][] = array('name'=>'brand_id', 'display' => 'Brand', 'type' => 'multi', 'options'=> $filter_brands);
    foreach($filters as $filter)
    {
        $ret = array('name' => 'filter_'.$filter['id'], 'display' => $filter['name'], 'type' => (($filter['type'] == 'multiple')?'multi':'single'), 'options'=>array());
        foreach($filter['items'] as $row)
        {
            $option = array('value' => $row['id'], 'display' => $row['name']);
            if(isset($filter_item_ids[$row['id']]))
                $option['on'] = true;
            if(!$row['count'])
                $option['disabled'] = true;
            $ret['options'][] = $option;
        }
        $json['filters'][] = $ret;
    }

    // price range
    $sql_temp  = $sql_where;
    unset($sql_temp['filter_item_ids']);
    unset($sql_temp['brand_id']);
    unset($sql_temp['price_min']);
    unset($sql_temp['price_max']);
    $results=$db->Execute(
        sprintf("
            SELECT
                MIN(shop_products.price) min
                ,MAX(shop_products.price) max
            FROM
                shop_products
            LEFT JOIN
                shop_product_options
            ON
                shop_product_options.product_id = shop_products.id
            LEFT JOIN
			    shop_refs
            ON
                shop_refs.product_id = shop_products.id
            WHERE
                %s
        "
            ,implode(' AND ', $sql_temp)
        )
    );
    $price_range = $results->FetchRow();
    $range_min = (int)floor($price_range['min']+0);
    $range_max = (int)ceil($price_range['max']+0);
    if($range_min == $range_max)
        $range_max++;

    $min = (int)floor(($price['min']?$price['min']:$price_range['min'])+0);
    $max = (int)ceil(($price['max']?$price['max']:$price_range['max'])+0);
    if($min == $max)
        $max++;
    $json['filters'][] = array('name' => 'price', 'display' => 'Price Range', 'type' => 'range', 'min' => $range_min, 'max'=>$range_max, 'value'=> array($min, $max));

	$json['sortby'] = array('options' => array('name' => 'Name', 'price_asc' => 'Price'));
	if($category['productord'] == 'manual')
		$json['sortby']['options'] = array('manual' => 'Choose') + $json['sortby']['options'];
	$sql_temp  = $sql_where;
	unset($sql_temp['filter_item_ids']);
	$db->Execute(
		$sql = sprintf("
			CREATE TEMPORARY TABLE 
				temp_stock
			SELECT
				shop_products.id product_id
				,SUM(shop_product_options.quantity) stock
			FROM
				shop_products
			LEFT JOIN
				shop_product_options
			ON
				shop_product_options.product_id = shop_products.id
            LEFT JOIN
			    shop_refs
            ON
                shop_refs.product_id = shop_products.id
			WHERE
				%s
			GROUP BY 
				shop_products.id
		"
			,implode(' AND ', $sql_temp)
		)
	);
    //die($sql);
	$db->Execute($sql = sprintf("ALTER TABLE temp_stock ADD INDEX i_product_id(product_id);"));
	$db->Execute($sql = sprintf("ALTER TABLE temp_stock ADD INDEX i_stock(stock);"));
	
	$sort = array();
	switch(strtolower($_REQUEST['sortby']))
	{
		case 'newest':
			$sort['field'] = 'shop_products.added';
			$sort['dir'] = 'DESC';
			$json['sortby']['selected'] = 'newest';
			break;
		case 'popular':
			$sort['field'] = 'shop_products.added';
			$sort['dir'] = 'ASC';
			$json['sortby']['selected'] = 'popular';
			break;
		case 'price_asc':
            $sort['field'] = 'shop_products.price';
            $sort['dir'] = 'ASC';
            $json['sortby']['selected'] = 'price_asc';
			break;
        default:
			if($category['productord'] == 'manual')
			{
				$sort['field'] = 'shop_products.ord';
				$sort['dir'] = 'ASC';
				$json['sortby']['selected'] = 'manual';
			}
			else
			{
				$sort['field'] = 'shop_products.price';
				$sort['dir'] = 'ASC';
				$json['sortby']['selected'] = 'price_asc';
			}
			break;
		case 'manual':
			$sort['field'] = 'shop_products.ord';
			$sort['dir'] = 'ASC';
			$json['sortby']['selected'] = 'manual';
			break;
		case 'price_desc':
			$sort['field'] = 'shop_products.price';
			$sort['dir'] = 'DESC';
			$json['sortby']['selected'] = 'price_desc';
			break;
		case 'name':
				$sort['field'] = 'shop_products.name';
				$sort['dir'] = 'ASC';
				$json['sortby']['selected'] = 'name';
			break;
	}
	
	switch(strtolower($_REQUEST['pageview']))
	{
		case 'all':
			$items_per_page = 1000000000;
			break;
		case '24':
			$items_per_page = 24;
			break;
		case '12':
		default:
			$items_per_page = 12;
			break;
	}
	
	$page = $_REQUEST['page']+0;
	if($page <= 0)
		$page = 1;
	
	$sql_where[] = sprintf("temp_stock.stock > 0");
	$item_count=$db->Execute(
		$sql=sprintf("
			SELECT
				COUNT(DISTINCT shop_products.id) count
			FROM
				shop_products
			LEFT JOIN
				shop_product_options
			ON
				shop_product_options.product_id = shop_products.id
			LEFT JOIN
				temp_stock
			ON
				temp_stock.product_id = shop_products.id
			LEFT JOIN
			    shop_refs
            ON
                shop_refs.product_id = shop_products.id
			%s
			WHERE
				%s
		"
			,implode(' ', $sql_join)
			,implode(' AND ', $sql_where)
		)
	);
	$item_count = $item_count->FetchRow();
	$item_count = $item_count['count'];
	
	$json['paging'] = array('total' => ceil($item_count / $items_per_page), 'current' => $page, 'item_count' => $item_count, 'items_per_page' => $items_per_page);
	
	$results=$db->Execute(
		$sql=sprintf("
			SELECT DISTINCT
				shop_products.*
				,shop_product_images.id image_id
				,shop_product_images.imagetype image_type
				,MIN(shop_product_options.price) min_price
				,MAX(shop_product_options.price) max_price
			FROM
				shop_products
			LEFT JOIN
				shop_product_images
			ON
				shop_product_images.product_id = shop_products.id
			LEFT JOIN
				shop_product_options
			ON
				shop_product_options.product_id = shop_products.id
			LEFT JOIN
				temp_stock
			ON
				temp_stock.product_id = shop_products.id
			LEFT JOIN
			    shop_refs
            ON
                shop_refs.product_id = shop_products.id
			%s
			WHERE
				%s
			GROUP BY
				shop_products.id
			ORDER BY
				%s %s
			LIMIT
				%u, %u
		"
			,implode(' ', $sql_join)
			,implode(' AND ', $sql_where)
			,$sort['field']
			,$sort['dir']
			,($page - 1)*$items_per_page
			,$items_per_page
		)
	);
    //die($sql);
	$products_out = '';
    $index = 0;
	while($row = $results->FetchRow())
	{
        $index++;
		$name = array();
		if(($var = trim($row['code'])) != '')
			$name[] = $var;
		if(($var = trim($row['name'])) != '')
			$name[] = $var;
		$name = implode(' - ', $name);
		
		if(trim($row['image_type']) != '')
		{
			switch($category['listing_type'])
			{
				case 'horizontal':
					$image = '<img src="'.$config['dir'].'images/product/listing_horizontal/'.$row['image_id'].'.'.$row['image_type'].'" alt="" width="215" height="137" />';
					break;
				case 'vertical':
					$image = '<img src="'.$config['dir'].'images/product/listing_vertical/'.$row['image_id'].'.'.$row['image_type'].'" alt="" width="215" height="274" />';
					break;
				default:
					$image = '<img src="'.$config['dir'].'images/product/'.$row['image_id'].'.'.$row['image_type'].'" alt="" width="282" height="282" />';
					break;
			}
		}
		else
			$image = '<img src="'.$config['dir'].'images/product/placeholder.jpg" alt="" width="282" height="282" />';
		
		switch($row['label'])
		{
			case 'new_product': $label = 'New product'; break;
			case 'on_sale': $label = 'Sale'; break;
			case 'best_seller': $label = 'Best Seller'; break;
			case 'bloch_stars': $label = 'Bloch Stars'; break;
			case 'none':
			default:
				$label = '';
				break;
		}
        if($label != '')
            $label = strtoupper($label).'!';

        $product_url = product_url($row['id'], $row['guid']);

        if($row['min_price']+0 != $row['max_price']+0)
            $price = price($row['price']+$row['min_price']).' to '.price($row['price']+$row['max_price']);
        else
            $price = price($row['price']);

		$products_out.= '
		    <li '.(($index%3 == 0)?'class="omega"':'').'>
                <h2><a href="'.$product_url.'">'.$label.'<span>'.strtoupper($name).($row['hide_price']?'':'<em> '.$price.'</em>').'</span></a></h2>
                <a href="'.$product_url.'">'.$image.'</a>
                <div class="btn-box">'.($row['hide_quick_view']?'':'<a href="'.quick_product_url($row['id'], $row['guid']).'" class="btn grey-btn quick-view">preview</a>').($row['hide_more_details']?'':'<a href="'.$product_url.'" class="btn golden-btn">DETAIL</a>').'</div>
            </li>';
	}
	
	if($products_out == '')
		$products_out = '
			<li style="width: 100%;">
                <p>Your selection returned no results - please widen your search criteria.</p>
			</li>';
	
	$json['products'] = $products_out;
	
	exit(json_encode($json));
?>