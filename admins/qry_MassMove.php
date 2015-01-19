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
	//References, products or both?
	if(isset($_POST['ref']))
	{
		$query="SELECT
				shop_refs.id
				,shop_products.name
			FROM
				shop_refs
				,shop_products
			WHERE
				shop_products.id=shop_refs.product_id
			AND (\n";
		$num=count($_POST['ref']);
		for($i=0;$i<$num;$i++)
		{
			if($i>0)
				$query.="OR ";
			$query.="shop_refs.id=%u\n";
		}
		$query.=") ORDER BY shop_products.name ASC";
		$confirm_ref=$db->Execute(
			vsprintf($query,$_POST['ref'])
		);
	}
	if(isset($_POST['product']))
	{
		$query="SELECT
				id
				,name
			FROM
				shop_products
			WHERE\n";
		$num=count($_POST['product']);
		for($i=0;$i<$num;$i++)
		{
			if($i>0)
				$query.="OR ";
			$query.="id=%u\n";
		}
		$query.=" ORDER BY name ASC";
		$confirm=$db->Execute(
			vsprintf($query,$_POST['product'])
		);
	}
?>
