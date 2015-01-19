<?
	/**
	 * e-Commerce System Data Feed/Export Plugin
	 * Copyright (c) 2002-2006 Philip John, All Rights Reserved.
	 * Author	: Philip John
	 * Version	: 6.0
	 *
	 * PROPRIETARY/CONFIDENTIAL.  Use is subject to license terms.
	 */
?>
<?
	if($type=="product")
		$image=$db->Execute(
			sprintf("
				SELECT
					id
					,imagetype
					,name
				FROM
					shop_products
				WHERE
					id=%u
			"
				,$imageid
			)
		);
	else if($type=="image")
		$image=$db->Execute(
			sprintf("
				SELECT
					shop_product_images.id
					,shop_product_images.imagetype
					,shop_products.name AS product_name
				FROM
					shop_product_images
					,shop_products
				WHERE
					shop_products.id=shop_product_images.product_id
				AND
					shop_product_images.id=%u
			"
				,$imageid
			)
		);
?>