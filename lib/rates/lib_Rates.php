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
	function fx($from,$to,$amount)
	{
		global $config;
		if($config["rates"][$from]>0)
			return ($amount/$config["rates"][$from])*$config["rates"][$to];
		else
			return false;
	}
?>