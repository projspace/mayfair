<?
	$keyword = safe($_GET['keyword']);
	
	/* PRODUCTS */
	$search=new Search($config);
	$hits=$search->find($keyword);

	$product_ids = array();
	$page_ids = array();
	foreach($hits as $hit)
	{
		$type = strtolower(trim($hit->getDocument()->getFieldValue('type')));
		$key_id = $hit->getDocument()->getFieldValue('key_id') + 0;
		
		$row = array('type'=>$type
					, 'key_id'=>$hit->getDocument()->getFieldValue('key_id')
					, 'title'=>$hit->getDocument()->getFieldValue('title')
					, 'abridged'=>$hit->getDocument()->getFieldValue('abridged')
					, 'fields'=>$hit->getDocument()->getFieldNames());

		if($type == 'product')
		{
			$product_ids[] = $key_id;
			$row['code'] = $hit->getDocument()->getFieldValue('code');
		}
		if($type == 'page')
			$page_ids[] = $key_id;
			
		$doc[] = $row;
	}
	//var_dump($product_ids, $page_ids, $doc);exit;
	$items_per_page = 5;
	// products
	$page_products = safe($_REQUEST['page_products'])+0;
	if($page_products <= 0)
		$page_products = 1;
	
	$sql_where = array();
	$sql_where[] = sprintf("shop_products.id > 1");
	$sql_where[] = sprintf("shop_products.category_id != 0");
	$sql_where[] = sprintf("shop_products.hidden = 0");
	$sql_where[] = sprintf("shop_products.parent_id = 0");
	//$sql_where[] = sprintf("shop_products.product_search = 1");
	
	if(count($product_ids))
		$sql_where[] = sprintf("shop_products.id IN (%s)", implode(',', $product_ids));
	else
		$sql_where[] = sprintf("0");
		
	$item_count_products=$db->Execute(
		sprintf("
			SELECT
				COUNT(DISTINCT shop_products.id) count
			FROM
				shop_products
			WHERE
				%s
		"
			,implode(' AND ',$sql_where)
		)
	);
	$item_count_products = $item_count_products->FetchRow();
	$item_count_products = $item_count_products['count']+0;
	
	$products=$db->Execute(
		$sql = sprintf("
			SELECT
				shop_products.*
				,shop_product_images.id image_id
				,shop_product_images.imagetype image_type
			FROM
				shop_products
			LEFT JOIN
				shop_product_images
			ON
				shop_product_images.product_id = shop_products.id
			WHERE
				%s
			GROUP BY
				shop_products.id
			LIMIT
				%u, %u
		"
			,implode(' AND ',$sql_where)
			,($page_products - 1)*$items_per_page
			,$items_per_page
		)
	);
	
	/* PAGES */
	$page_pages = safe($_REQUEST['page_pages'])+0;
	if($page_pages <= 0)
		$page_pages = 1;
	
	$sql_where = array();
	$sql_where[] = sprintf("cms_pages.hidden = 0");
	$sql_where[] = sprintf("cms_pages.deleted = 0");
	
	if(count($page_ids))
		$sql_where[] = sprintf("cms_pages.id IN (%s)", implode(',', $page_ids));
	else
		$sql_where[] = sprintf("0");
		
	$item_count_pages=$db->Execute(
		sprintf("
			SELECT
				COUNT(DISTINCT cms_pages.id) count
			FROM
				cms_pages
			WHERE
				%s
		"
			,implode(' AND ',$sql_where)
		)
	);
	$item_count_pages = $item_count_pages->FetchRow();
	$item_count_pages = $item_count_pages['count']+0;
	
	$pages=$db->Execute(
		$sql = sprintf("
			SELECT
				cms_pages.*
				,cms_content.description
			FROM
				cms_pages
			LEFT JOIN
				cms_content
			ON
				cms_content.pageid = cms_pages.id
			AND
				cms_content.revision = cms_pages.revision
			WHERE
				%s
			GROUP BY
				cms_pages.id
			LIMIT
				%u, %u
		"
			,implode(' AND ',$sql_where)
			,($page_pages - 1)*$items_per_page
			,$items_per_page
		)
	);
?>