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
	//Search heading
	$smarty->display($config['template']."/shop/search.tpl.php");

        //Pagination
        if($num->fields['num']>9)
                $smarty->assign("pagination",true);
        if($start>0)
                $smarty->assign("showprev",true);
        if($start+9<$num->fields['num'])
                $smarty->assign("shownext",true);

        $smarty->assign("num",$num->fields['num']);
        $smarty->assign("start",$start);
        $smarty->assign("link","shop.priceSearch&price=".$price);
	$smarty->assign("pages",ceil((float) $num->fields['num']/9));
        $smarty->display($config['template']."/shop/pagination_search.tpl.php");


	//Products
	if($products->RecordCount()>0)
	{
		$smarty->assign("category_id",$category_id);
		$smarty->assign("products",$products->GetRows());
		$smarty->display($config['template']."/shop/products.tpl.php");
	}
	else
		$smarty->display($config['template']."/shop/no_products.tpl.php");

        $smarty->display($config['template']."/shop/pagination_search.tpl.php");

?>
