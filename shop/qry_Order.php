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
	$order=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				shop_orders
			WHERE
				id=%u
		"
			,$order_id
		)
	);
	$products=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				shop_products
				,shop_order_products
				,shop_brands
			WHERE
				shop_brands.id=shop_products.brand_id
			AND
				shop_products.id=shop_order_products.product_id
			AND
				shop_order_products.order_id=%u
		"
			,$order_id
		)
	);
?>