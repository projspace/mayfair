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
	define('SMARTY_DIR',$config['path']."lib/smarty/");
	include(SMARTY_DIR."Smarty.class.php");
	$smarty = new Smarty;
	$smarty->template_dir = $config['path']."layout/smarty/templates/";
	$smarty->compile_dir = $config['path']."layout/smarty/templates_c/";
	$smarty->config_dir = $config['path']."layout/smarty/configs/";
	$smarty->cache_dir = $config['path']."cache/smarty/";
	$smarty->plugins_dir = SMARTY_DIR."plugins/";
	$smarty->assign("config",$config);
	if(!USECOOKIE && !SEARCHENGINE)
	{
		$smarty->assign("sid_amp","&amp;".urlencode($config['shop']['session_id'])."=".urlencode($$config['shop']['session_id']));
		$smarty->assign("sid","?".urlencode($config['shop']['session_id'])."=".urlencode($$config['shop']['session_id']));
		$smarty->assign("sid_form","<input type=\"hidden\" name=\"".$config['shop']['session_id']."\" value=\"".$$config['shop']['session_id']."\" />");
	}
?>