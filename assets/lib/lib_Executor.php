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
	class Executor
	{
		var $_vars;
		var $_path;

		function Executor($vars,$path)
		{
			$this->_vars=$vars;
			$this->_path=$path;
		}

		function calc()
		{
			$shipping_vars=$this->_vars;
			include($this->_path."lib/cfg_CheckoutRulesCache.php");
			return $shipping_vars;
		}
	}

	function notallowed($a=false)
	{
		//Not allowed
	}
?>