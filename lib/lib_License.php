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
	class License
	{
		var $ip="127.0.0.1";
		var $customer="Phil John";

		function state($function)
		{
			if($license['function'])
				return "";
			else
				return "disabled/";
		}


	}
?>