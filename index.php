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
<?php
	// Report simple running errors
	error_reporting ( 0 );

	session_start();
	ob_start();

	//Bring in options and config
	include("lib/cfg_Options.php");
	include("lib/cfg_Config.php");
	include("lib/cfg_SearchEngine.php");

	//Redirect to proper domain if client comes in from anywhere else
	if($_SERVER['HTTP_HOST']!=$config['url'])
	{
		header("location: http://".$config['url'].$_SERVER['REQUEST_URI']);
		die();
	}
	
	if(preg_match('/(android)|(blackberry)|(ipad)|(iphone)|(ipod)|(iemobile)|(opera mobile)|(palmos)|(webos)|(googlebot-mobile)/i', $_SERVER['HTTP_USER_AGENT']))
		define('MOBILE_DEV', true);
	else
		define('MOBILE_DEV', false);
	
	ob_start();

	//Detect if user is a search engine
	$useragent=strtoupper(preg_replace("/[^A-Za-z]*/","",$_SERVER['HTTP_USER_AGENT']));
	$check=false;
	foreach($config['searchengine'] as $searchengine)
	{
		if(strstr($useragent,$searchengine))
		{
			$check=true;
			break;
		}
	}
	if($check)
	{
		define('SEARCHENGINE',true);
		define('USECOOKIE',false);
		define('TESTCOOKIE',false);
	}
	else
		define('SEARCHENGINE',false);

	//Detect use of cookies
	if(	(isset($_GET[$config['shop']['session_id']]) || isset($_POST[$config['shop']['session_id']])) && !isset($_COOKIE[$config['shop']['session_id']]))
	{
		define('USECOOKIE',false);
		define('TESTCOOKIE',false);
	}
	else if(isset($_COOKIE[$config['shop']['session_id']]))
	{
		define('USECOOKIE',true);
		define('TESTCOOKIE',false);
	}
	else
	{
		define('USECOOKIE',false);
		define('TESTCOOKIE',true);
	}

	//Errorhandler to display pretty error messages
	include("lib/lib_ErrorHandler.php");

	//Make sure content isn't cached
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
   	header("Cache-Control: private, no-store, no-cache, must-revalidate");
   	header("Cache-Control: post-check=0, pre-check=0", false);
   	header("Pragma: no-cache");
   	header("Connection: Keep-Alive");
   	header("Keep-Alive: timeout=10, max=100");
	
   	//Turn SEO URL parameters into global vars
	if(isset($_GET['req']))
	{
		$req=explode("/",$_GET['req']);
		$reqlen=count($req);
		for($i=1;$i<$reqlen;$i=$i+2)
		{
			$request[$req[$i]]=urldecode($req[$i+1]);
			$$req[$i]=$req[$i+1];
		}
	}

	if(!isset($act))
		$act="";
	require("fbx_Fusebox3.0_PHP4.1.x.php");
	ob_end_flush();
?>
