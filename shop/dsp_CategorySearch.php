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
	//Category heading
	if($start==0)
	{
		$smarty->assign("category",$details->FetchRow());
		$smarty->display($config['template']."/shop/category.tpl.php");
	}

	//Category search engine
	if(isset($search_params))
        {
		$smarty->assign("search_params",$search_params);
		$smarty->assign("search_keys",array_keys($search_params));
		$smarty->display($config['template']."/shop/category_search.tpl.php");
	}

	//Child Categories
	if(!$children->EOF)
	{
		$smarty->assign("children",array_chunk($children->GetRows(),$config['display']['children']));
		$smarty->display($config['template']."/shop/child_categories.tpl.php");
	}

	//Refs
	if(!$refs->EOF)
	{
		$smarty->assign("refs",array_chunk($refs->GetRows(),$config['display']['refs']));
		$smarty->display($config['template']."/shop/references.tpl.php");
	}

	//Pagination
	if($config['display']['products']>0)
	{
		$smarty->assign("num",$num->fields['num']);
		if($num->fields['num']>$config['display']['products'])
			$smarty->assign("pagination",true);
		$smarty->assign("display",$config['display']['products']);
		$smarty->assign("start",$start);
		if($start>0)
			$smarty->assign("showprev",true);
		if($start+$config['display']['products']<$num->fields['num'])
			$smarty->assign("shownext",true);
		$smarty->assign("link","shop.category/category_id/".$category_id);
		$smarty->assign("pages",ceil((float) $num->fields['num']/$config['display']['products']));
		$smarty->display($config['template']."/shop/pagination.tpl.php");
	}

	//Products
	if($products->RecordCount()>0)
	{
		$smarty->assign("category_id",$category_id);
		$smarty->assign("products",$products->GetRows());
		$smarty->display($config['template']."/shop/products.tpl.php");
	}

	if($config['display']['products']>0)
	{
		$smarty->assign("num",$num->fields['num']);
		$smarty->assign("start",$start);
		$smarty->assign("link","shop.category/category_id/".$category_id);
		$smarty->assign("pages",ceil((float) $num->fields['num']/$config['display']['products']));
		$smarty->display($config['template']."/shop/pagination.tpl.php");
	}
?>
