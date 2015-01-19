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
	class PSP
	{
		var $smarty;
		var $config;
		var $db;
		var $session;

		function PSP(&$config,&$smarty,&$db)
		{
			$this->config=$config;
			$this->smarty=$smarty;
			$this->db=$db;
		}

		function setSession(&$session)
		{
			$this->session=$session;
		}
		
		function Checkout(&$params)
		{
			$this->smarty->assign("params",$params);
			$this->smarty->display($this->config["template"]."/payment/checkout.tpl.php");
		}

		function Details(&$params)
		{
			$this->smarty->assign("params",$params);
			$this->smarty->display($this->config["template"]."/payment/details.tpl.php");
		}
		
		function CheckComplete(&$params)
		{
			return false;
		}
		
		function SessionID(&$params)
		{
			return false;
		}
		
		function SaveDetails(&$params)
		{
			return $params;
		}

		function Callback(&$params)
		{
			return $params;
		}

		function Finished(&$params)
		{
			$this->smarty->display($this->config["template"]."/payment/finished.tpl.php");
		}

		function Declined(&$params)
		{
			$this->smarty->display($this->config["template"]."/payment/declined.tpl.php");
		}

		function Cancelled(&$params)
		{
			$this->smarty->display($this->config["template"]."/payment/cancelled.tpl.php");
		}

		function Invalid(&$params)
		{
			$this->smarty->display($this->config["template"]."/payment/invalid.tpl.php");
		}

		function Refund(&$params)
		{
			return false;
		}
		
		function GetTxnVars(&$params)
		{
			return false;	
		}
	}
?>
