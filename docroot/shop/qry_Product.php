<?
	$sql_where = array();
	$sql_where[] = sprintf("shop_products.id > 1");
	if(trim($_REQUEST['product_guid']) != '')
		$sql_where[] = sprintf("shop_products.guid=%s", $db->Quote($_REQUEST['product_guid']));
	else
		$sql_where[] = sprintf("shop_products.id=%u", $_REQUEST['product_id']);
	
	$product=$db->Execute(
		sprintf("
			SELECT
				shop_products.*
				,IF(shop_products.vat, shop_products.price*(100+%f)/100, shop_products.price) price
				,MIN(shop_product_options.price) min_price
				,MAX(shop_product_options.price) max_price
			FROM
				shop_products
            LEFT JOIN
				shop_product_options
			ON
				shop_product_options.product_id = shop_products.id
			WHERE
				%s
		"
			,VAT
			,implode(' AND ', $sql_where)
		)
	);
	$product = $product->FetchRow();
	if(!$product)
	{
		header("location: ".$config['dir']);
		exit;
	}
	$product_id = $product['id'];
	
	$db->Execute(
		sprintf("
			INSERT INTO
				shop_recent_products
			SET
				ip = %s
				,product_id = %u
				,time = NOW()
		"
			,$db->Quote($_SERVER['REMOTE_ADDR'])
			,$product['id']
		)
	);
	
	$category=$db->Execute(
		sprintf("
			SELECT
				shop_categories.*
			FROM
				shop_categories
			WHERE
				shop_categories.id=%u
		"
			,$product['category_id']
		)
	);
	$category = $category->FetchRow();
	if(!$category)
	{
		header("location: ".$config['dir']);
		exit;
	}
	
	// meta tags
	if($product['meta_title']!="")
		$elems->meta['title']=$product['meta_title'];
	else
		$elems->meta['title']=$product['name'].' / '.$config['meta']['title'];

	if($product['meta_description']!="")
		$elems->meta['description']=$product['meta_description'];
	else
		$elems->meta['description']=trim(strip_tags($product['description']));

	if($product['meta_keywords']!="")
		$elems->meta['keywords']=$product['meta_keywords'];
	
	// trail menu
	$trail['menu'] = array();
	/*foreach(unserialize($category['trail']) as $index=>$row)
	{
		if($index < 2)
			continue;
		$trail['menu'][] = array('url'=>category_url($row['id'],$row['name']), 'name'=>$row['name']);
	}*/
	$trail['menu'][] = array('url'=>product_url($product['id'],$product['guid']), 'name'=>$product['name']);
	//options
	$product['options']=unserialize($product['options']);
	
	//images
	$results=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				shop_product_images
			WHERE
				product_id=%u
			ORDER BY
				id ASC
		"
			,$product['id']
		)
	);
	$images = array();
	/*if($product['imagetype'] != '')
	{
		$pid = $product['parent_id']?$product['parent_id']:$product['id'];
		$images[] = array(
			'thumb' => $config['dir'].'images/product/small/product_'.$pid.'.'.$product['imagetype']
			,'view' => $config['dir'].'images/product/view/product_'.$pid.'.'.$product['imagetype']
			,'color_id' => ''
		);
	}*/
	while($row = $results->FetchRow()) 
	{
		$pid = $row['parent_id']?$row['parent_id']:$row['id']; 
		$img = array(
			'color_id' => $row['color_id']
		);
		if($product['zoom'] == 'portrait')
		{
			$img['thumb'] = $config['dir'].'images/product/large_thumb/'.$pid.'.'.$row['imagetype'];
			$img['zoom'] = $config['dir'].'images/product/large_zoom/'.$pid.'.'.$row['imagetype'];
			$img['view'] = $config['dir'].'images/product/view/'.$pid.'.'.$row['imagetype'];
			if(is_file($config['path'].'images/product/view/'.$pid.'.'.$row['imagetype']))
				$img_info = getimagesize($config['path'].'images/product/view/'.$pid.'.'.$row['imagetype']);
		}
		else
		{
			$img['thumb'] = $config['dir'].'images/product/small/'.$pid.'.'.$row['imagetype'];
			$img['zoom'] = $config['dir'].'images/product/zoom/'.$pid.'.'.$row['imagetype'];
			$img['view'] = $config['dir'].'images/product/view/'.$pid.'.'.$row['imagetype'];
			if(is_file($config['path'].'images/product/view/'.$pid.'.'.$row['imagetype']))
				$img_info = getimagesize($config['path'].'images/product/view/'.$pid.'.'.$row['imagetype']);
		}
		$img['view_width'] = $img_info[0];
		$img['view_height'] = $img_info[1];
		
		$images[] = $img;
	}
		
	if(!count($images))
		$images[] = array(
			'thumb' => $config['dir'].'images/product/small/placeholder.jpg' 
			,'view' => $config['dir'].'images/product/view/placeholder.jpg'
			,'zoom' => $config['dir'].'images/product/zoom/placeholder.jpg'
		);
		
	//360 images
	if($product['360_view'])
	{
		$image_360=$db->Execute(
			sprintf("
				SELECT
					*
				FROM
					shop_product_360_images
				WHERE
					product_id=%u
				LIMIT 1
			"
				,$product['id']
			)
		);
		$image_360 = $image_360->FetchRow();
		$info_360 = getimagesize($config['path'].'images/product/360_view/'.$image_360['id'].'.'.$image_360['image_type']);
	}

	//reviews
	$reviews=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				shop_product_reviews
			WHERE
				product_id=%u
			AND
				status = 'approved'
			ORDER BY
				posted DESC
		"
			,$product['id']
		)
	);
	
	//warnings
	$warnings=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				shop_product_warnings
			WHERE
				product_id=%u
			AND
			(
				`trigger` >= %u
			OR
				`trigger` = -1
			)
			ORDER BY
				`trigger` ASC
			LIMIT 2
		"
			,$product['id']
			,$product['stock']
		)
	);
	$warnings = $warnings->GetRows();
	
	//related products
	$related_products=$db->Execute(
		sprintf("
			SELECT
				shop_products.id
				,shop_products.name
				,shop_products.price
				,shop_products.guid
				,shop_products.imagetype
				,shop_products.parent_id
				,shop_product_images.id image_id
				,shop_product_images.imagetype image_type
			FROM
			(
				shop_product_similar
				,shop_products
			)
			LEFT JOIN
				shop_product_images
			ON
				shop_product_images.product_id = shop_products.id
			WHERE
				shop_product_similar.product_id=%u
			AND
				shop_product_similar.similar_product_id = shop_products.id
			AND
				shop_products.hidden = 0
			GROUP BY
				shop_products.id
			ORDER BY 
				RAND()
			LIMIT 3
		"
			,$product['id']
		)
	);
?>
