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
	if(!isset($ord))
		$ord="name";
	if(!isset($dir))
		$dir="ASC";

	$dirarr['name']="ASC";
	$dirarr['price']="ASC";
	$dirarr['size']="ASC";
	$dirarr['country']="ASC";

	switch($ord)
	{
		case "name":
			if($dir=="ASC")
				$dirarr['name']="DESC";
			else
				$dirarr['name']="ASC";
			break;
		case "price":
                        if($dir=="ASC")
                                $dirarr['price']="DESC";
                        else
                                $dirarr['price']="ASC";
			break;
		case "size":
			$ord="weight";
                        if($dir=="ASC")
                                $dirarr['size']="DESC";
                        else
                                $dirarr['size']="ASC";
			break;
		case "country":
			$ord="shop_brands.name";
                        if($dir=="ASC")
                                $dirarr['country']="DESC";
                        else
                                $dirarr['country']="ASC";
			break;
		default: $ord="name";
                       if($dir=="ASC")
                                $dirarr['name']="DESC";
                        else
                                $dirarr['name']="ASC";
                        break;
	}

	if($dir!="ASC")
		$dir="DESC";

	$products=$db->Execute(
		sprintf("
			SELECT
				shop_products.*
				,shop_brands.name AS brand_name
			FROM
				shop_products
				,shop_brands
			WHERE
				shop_brands.id=shop_products.brand_id
			AND
				shop_products.id>1
			AND
				shop_products.parent_id=0
			ORDER BY
				%s %s
		"
			,$ord
			,$dir
		)
	);

?>
