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
	$category['name']="All Models";
	$smarty->assign("category",$category);
	$smarty->display($config['template']."/shop/title.tpl.php");
	$smarty->assign("dir",$dirarr);
	//Products
	if($products->RecordCount()>0)
	{
		$smarty->assign("products",$products->GetRows());
		$smarty->display($config['template']."/shop/models.tpl.php");
	}
?>
