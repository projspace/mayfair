<?
	/**
	 * e-Commerce System
	 * Copyright (c) 2002-2006 Philip John, All Rights Reserved.
	 * Author	: Philip John
	 * Version	: 6.0
	 *
	 * PROPRIETARY/CONFIDENTIAL.  Use is subject to license terms.
	 */
?>
<?
	$page=$db->Execute(
		sprintf("
			SELECT
				cms_pages.id
				,cms_pages.name
				,cms_pages.identifier
				,cms_pages.revision
				,cms_pages.content_revision
				,cms_pages.parent_id
				,cms_pages.lft
				,cms_pages.rgt
				,cms_pages.pagetype
				,cms_layouts.filename AS layout_filename
				,cms_layouts.type AS layout_type
			FROM
				cms_pages
				,cms_layouts
			WHERE
				cms_pages.id=%u
			AND
				cms_pages.siteid=%u
			AND
				cms_pages.pendingadd=0
			AND
				cms_pages.deleted=0
			AND (
					cms_pages.valid_from<=%s
					OR cms_pages.valid_from IS NULL
			)
			AND (
					cms_pages.valid_to>=%s
					OR cms_pages.valid_to IS NULL
			)
			AND
				cms_layouts.id=cms_pages.layoutid
		"
			,$pageid
			,$config['siteid']
			,$db->DBTimeStamp(time())
			,$db->DBTimeStamp(time())
		)
	);
	$page = $page->FetchRow();

	$content=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				cms_content
			WHERE
				pageid=%u
			AND
				revision=%u
		"
			,$page['id']
			,$page['content_revision']
		)
	);
	$elems->setPage($page,$content);
	
	// meta tags
	if($content->fields['meta_title']!="")
		$elems->meta['title']=$content->fields['meta_title'];

	if($content->fields['meta_description']!="")
		$elems->meta['description']=$content->fields['meta_description'];

	if($content->fields['meta_keywords']!="")
		$elems->meta['keywords']=$content->fields['meta_keywords'];
	
	$images=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				cms_pages_images
			WHERE
				pageid=%u
			ORDER BY
				ord ASC
		"
			,$page['id']
		)
	);
	
	$count=$db->Execute(
		sprintf("
			SELECT
				COUNT(*) count
			FROM
				cms_pages
			WHERE
				parent_id = %u
			AND
				hidden = 0
			AND
				deleted=0
			AND
				sidebar = 1
		"
			,$page['id']
		)
	);
	$count = $count->FetchRow();
	$count = $count['count'];
	
	if($count)
		$sidebar=$db->Execute(
			sprintf("
				SELECT
					id
					,name
					,url
				FROM
					cms_pages
				WHERE
				(
					id = %u
				OR
					parent_id = %u
				)
				AND
					hidden = 0
				AND
					deleted=0
				AND
					sidebar = 1
				ORDER BY
					parent_id ASC
					,ord ASC
			"
				,$page['id']
				,$page['id']
			)
		);
	else
		$sidebar=$db->Execute(
			sprintf("
				SELECT
					id
					,name
					,url
				FROM
					cms_pages
				WHERE
				(
					id = %u
				OR
					parent_id = %u
				)
				AND
					hidden = 0
				AND
					deleted=0
				AND
					sidebar = 1
				ORDER BY
					parent_id ASC
					,ord ASC
			"
				,$page['parent_id']
				,$page['parent_id']
			)
		);

    if($page['identifier'] == 'shipping-policy')
    {
        $rates=$db->Execute(
			sprintf("
				SELECT DISTINCT
					shipping_options.*
					,shipping_option_prices.value
					,shipping_option_prices.price
				FROM
					shipping_options
				JOIN
					shipping_option_prices
				ON
					shipping_option_prices.option_id = shipping_options.id
				ORDER BY
					shipping_options.id ASC
					,shipping_option_prices.value ASC
			"
			)
		);
    }
		
	if($page['layout_type'] == 'all-stars')
	{
		$db->Execute(
			sprintf("
				CREATE TEMPORARY TABLE 
					temp_cpi
				SELECT
					id
					,pageid
					,image_type
					,ratio
				FROM
					cms_pages_images
				WHERE
					1
				ORDER BY
					ord ASC
			"
				,$page['id']
			)
		);
		$db->Execute($sql = sprintf("ALTER TABLE temp_cpi ADD INDEX i_pageid(pageid);"));

		$children=$db->Execute(
			sprintf("
				SELECT DISTINCT
					cms_pages.id
					,cms_pages.name
					,cms_pages.url
					,cms_content.description
					,temp_cpi.id image_id
					,temp_cpi.image_type
					,temp_cpi.ratio
				FROM
					cms_pages
				LEFT JOIN
					cms_content
				ON
					cms_content.pageid = cms_pages.id
				AND
					cms_content.revision = cms_pages.content_revision
				LEFT JOIN
					temp_cpi
				ON
					temp_cpi.pageid = cms_pages.id
				WHERE
					cms_pages.parent_id = %u
				AND
					cms_pages.hidden = 0
				AND
					cms_pages.deleted=0
				AND
					cms_pages.sidebar = 1
				GROUP BY
					cms_pages.id
				ORDER BY
					cms_pages.ord ASC
			"
				,$page['id']
			)
		);
	}
	
	if($page['layout_type'] == 'fitting')
	{
		$guide_ids = array();
		$column_ids = array();
		if($_REQUEST['product_id']+0)
		{
			$product=$db->Execute(
				sprintf("
					SELECT
						id
						,category_id
					FROM
						shop_products
					WHERE
						id = %u
				"
					,$_REQUEST['product_id']
				)
			);
			$product = $product->FetchRow();
			$category_id = $product['category_id']+0;
			
			$results=$db->Execute(
				sprintf("
					SELECT
						column_id
					FROM
						shop_product_fitting_guide_columns
					WHERE
						product_id = %u
				"
					,$product['id']
				)
			);
			while($row = $results->FetchRow())
				$column_ids[$row['column_id']] = 1;
		}
		else
			$category_id = $_REQUEST['category_id']+0;

		if($category_id)
		{
			$results=$db->Execute(
				sprintf("
					SELECT
						guide_id
					FROM
						shop_category_fitting_guides
					WHERE
						category_id = %u
				"
					,$category_id
				)
			);
			while($row = $results->FetchRow())
				$guide_ids[$row['guide_id']] = 1;
				
			$results=$db->Execute(
				sprintf("
					SELECT
						column_id
					FROM
						shop_category_fitting_guide_columns
					WHERE
						category_id = %u
				"
					,$category_id
				)
			);
			while($row = $results->FetchRow())
				$column_ids[$row['column_id']] = 1;
		}

		$sql_where = array();
		if(count($guide_ids))
			$sql_where[] = sprintf("shop_fitting_guides.id IN (%s)", implode(',', array_keys($guide_ids)));
		if(count($column_ids))
			$sql_where[] = sprintf("shop_fitting_guide_columns.id IN (%s)", implode(',', array_keys($column_ids)));
		if(!count($sql_where))
			$sql_where[] = '1';
		
		$results=$db->Execute(
			sprintf("
				SELECT
					shop_fitting_guides.id row_id
					,shop_fitting_guides.ord row_index
					,shop_fitting_guides.name row_name
					,shop_fitting_guides.heading
					,shop_fitting_guide_columns.id column_id
					,shop_fitting_guide_columns.ord column_index
					,shop_fitting_guide_columns.name column_name
					,shop_fitting_guide_sizes.size
				FROM
				(
					shop_fitting_guides
					,shop_fitting_guide_columns
				)
				LEFT JOIN
					shop_fitting_guide_sizes
				ON
					shop_fitting_guide_sizes.guide_id = shop_fitting_guides.id
				AND
					shop_fitting_guide_sizes.column_id = shop_fitting_guide_columns.id
				WHERE
					%s
				ORDER BY
					shop_fitting_guides.ord ASC
					,shop_fitting_guide_columns.ord ASC
			"
				,implode(' AND ', $sql_where)
			)
		);
		$fitting_guides = array();
		$fitting_guides_rows = array();
		$fitting_guides_columns = array();
		while($row = $results->FetchRow())
		{
			$fitting_guides_rows[$row['row_index']] = $row;
			$fitting_guides_columns[$row['column_index']] = 1;
			$fitting_guides[$row['row_index'].','.$row['column_index']] = $row;
		}
	}
	
	$Fusebox["layoutFile"]=$page['layout_filename'];
?>