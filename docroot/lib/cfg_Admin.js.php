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
	include("cfg_Options.php");
	include("cfg_Config.php");
?>
var dir='<?= $config['dir'] ?>';
var options=new Array(<?= OPTIONS ?>);
var specs=1;
var files={};
var fields=1;