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
	$db =& NewADOConnection($config['db']['driver']);
	$db->setMagicQuotes(get_magic_quotes_gpc());
	$db->Connect($config['db']['server'],$config['db']['username'],$config['db']['password'],$config['db']['database']) or die("Unable to connect");
?>