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
	$vars['country']=$_POST['country'];
	$vars['area']=$_POST['area'];
	$vars['shipping']=$_POST['shipping'];
	$vars['total']=$_POST['total'];
	$vars['nitems']=$_POST['nitems'];
	$vars['weight']=$_POST['weight'];
	$vars['packing']=$_POST['packing'];
	$vars['coupon']=$_POST['coupon'];

	$code=file_get_contents($config['path']."lib/cfg_CheckoutRulesCache.php");
	$code=substr($code,strpos($code,"\n"));
	$executor=new Executor($vars, $config['path']);
	$vars=$executor->calc($code);


	$output=$vars;
?>