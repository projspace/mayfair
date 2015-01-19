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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title><?= PRODUCT_NAME." ".PRODUCT_VERSION; ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	
	<link rel="stylesheet" href="<?= $config["dir"] ?>admin-assets/css/reset.css"/>
	<link rel="stylesheet" href="<?= $config["dir"] ?>admin-assets/css/style.css"/>
	<link rel="stylesheet" href="<?= $config["dir"] ?>admin-assets/css/buttons.css"/>
	<link rel="stylesheet" href="<?= $config["dir"] ?>admin-assets/css/utils.css"/>

	<link rel="stylesheet" href="<?= $config["dir"] ?>admin-assets/js/jquery.ui/css/mytheme/style.css"/>
	<link rel="stylesheet" href="<?= $config["dir"] ?>admin-assets/js/fancybox/jquery.fancybox-1.3.1.css"/>
	
	<script type="text/javascript" src="<?= $config["dir"] ?>admin-assets/js/jquery.min.js"></script>
	<script type="text/javascript" src="<?= $config["dir"] ?>admin-assets/js/jquery.ui/jquery.ui.custom.js"></script>
	<script type="text/javascript" src="<?= $config["dir"] ?>admin-assets/js/libraries.js"></script>
	<script type="text/javascript" src="<?= $config["dir"] ?>admin-assets/js/ddroundies.js"></script>
	<!--[if lt IE 9]>
	<script type="text/javascript" src="http://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE9.js"></script>
	<![endif]-->

	<script type="text/javascript" src="<?= $config["dir"] ?>admin-assets/js/fancybox/jquery.fancybox-1.3.1.pack.js"></script>
	<script type="text/javascript" src="<?= $config["dir"] ?>admin-assets/js/main.js"></script>
	<script type="text/javascript">
	    //<![CDATA[
		var date_format = 'M dd, YY'
	    //]]>
	</script>

	<script language="JavaScript" type="text/Javascript" src="<?= $config["dir"] ?>lib/cfg_Admin.js.php"></script>
	<script language="JavaScript" type="text/javascript" src="<?= $config["dir"] ?>lib/lib_Admin.js"></script>
</head>

<body class="login">
	<div id="wrapper">
		<div id="header">
			<a class="title" href="/admin">Administrative <span>panel</span></a>
			<ul id="menu"></ul>
			<?php alertRender(); ?>
		</div>
		<div id="middle">
			<? print trim($Fusebox["layout"]); ?>
		</div>
	</div>
</body>
</html>
