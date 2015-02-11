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
	$brands=$db->Execute("
		SELECT
			shop_brands.id
			,shop_brands.name
			,shop_brands.url
			,shop_suppliers.name AS supplier_name
			,shop_suppliers.id AS supplier_id
		FROM
			shop_brands
			,shop_suppliers
		WHERE
			shop_suppliers.id=shop_brands.supplier_id
		ORDER BY
			shop_brands.name
		ASC
	");
?>