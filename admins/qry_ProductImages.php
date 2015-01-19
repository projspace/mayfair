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
	$images=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				shop_product_images
			WHERE
				product_id=%u
			ORDER BY
				id
			ASC
		"
			,$_REQUEST['product_id']
		)
	);
	
	$images_360=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				shop_product_360_images
			WHERE
				product_id=%u
			ORDER BY
				id ASC
		"
			,$_REQUEST['product_id']
		)
	);
?>