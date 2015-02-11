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
	if(!isset($_REQUEST['category_id']))
		$_REQUEST['category_id']=1;

	$category=$db->Execute(
		sprintf("
			SELECT
				id
				,trail
				,childord
				,productord
			FROM
				shop_categories
			WHERE
				id=%u
		"
			,$_REQUEST['category_id']
		)
	);
	$history=unserialize($category->fields['trail']);

	$children=$db->Execute(
		sprintf("
			SELECT DISTINCT
				shop_categories.id
				,shop_categories.name
				,shop_categories.parent_id
				,shop_categories.link_category_id
				,shop_categories.no_landing_page
				,COUNT(DISTINCT sc.id) children
			FROM
				shop_categories
			LEFT JOIN
				shop_categories sc
			ON
				sc.parent_id = shop_categories.id
			WHERE
				shop_categories.parent_id=%u
			GROUP BY
				shop_categories.id
			ORDER BY
				%s ASC
		"
			,$_REQUEST['category_id']
			,$category->fields['childord'] ? 'shop_categories.ord' : 'shop_categories.name'
		)
	);
?>
