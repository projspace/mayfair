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
	//header("location: ".str_replace('http://', 'https://', $config['dir'])."index.php/fuseaction/shop.".$order['status']);
	header("location: ".$config['dir']."index.php/fuseaction/shop.".$order['status']);
?>