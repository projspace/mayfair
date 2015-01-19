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
	$params['session_id']=$session->session_id;
	$params['country_id']=$country_id;
	$params['vars']=$vars;
	$params['billing']=$billing;
	$params['delivery']=$delivery;
	$params['request']=$_REQUEST;
	$params['cart_count']=count($rows);
?>
