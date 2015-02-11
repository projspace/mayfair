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
        if(!isset($start))
                $start=0;

        if($start<0)
                $start=0;

	if($price==1001)
		$priceeq=">";
	else
		$priceeq="<=";

	$query="SELECT
			shop_brands.id AS brand_id
			,shop_brands.name AS brand_name
			,shop_brands.imagetype AS brand_imagetype
			,shop_products.id
			,shop_products.parent_id
			,shop_products.name
			,shop_products.price
			,shop_products.weight
			,shop_products.description
			,shop_products.imagetype
			,shop_products.soldout
			,shop_products.stock
			,shop_products.options
		FROM
			shop_products
			,shop_brands
		WHERE
			shop_brands.id=shop_products.brand_id
		AND
			shop_products.id>1
		AND
			shop_products.price".$priceeq."%u
		AND
			shop_products.parent_id=0
		ORDER BY
			shop_products.price ASC
		LIMIT
			%u,9";

        $nquery="SELECT
			COUNT(shop_products.id) AS num
                FROM
                        shop_products
                        ,shop_brands
                WHERE
                        shop_brands.id=shop_products.brand_id
                AND
                        shop_products.id>1
		AND
			shop_products.parent_id=0
                AND
                        shop_products.price".$priceeq."%u";

	$num=$db->Execute(sprintf($nquery,$price));

	$products=$db->Execute(sprintf($query,$price,$start));
	echo $db->ErrorMsg();
?>
