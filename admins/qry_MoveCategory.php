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
	if(!$_REQUEST['parent_id'])
		$_REQUEST['parent_id']=1;

	$category=$db->Execute(
		sprintf("
			SELECT
				trail
				,childord
			FROM
				shop_categories
			WHERE
				id=%u
		"
			,$_REQUEST['parent_id']
		)
	);
	$history=unserialize($category->fields['trail']);

	$children=$db->Execute(
		sprintf("
			SELECT
				id
				,name
			FROM
				shop_categories
			WHERE
				parent_id=%u
			ORDER BY
				%s
			ASC
		"
			,$_REQUEST['parent_id']
			,$category->fields['childord'] ? 'ord' : 'name'
		)
	);
?>