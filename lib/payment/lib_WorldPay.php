<?
	/**
	 * e-Commerce System Payment Provider Plugin
	 * Copyright (c) 2002-2006 Philip John, All Rights Reserved.
	 * Author	: Philip John
	 * Version	: 6.0
	 *
	 * PROPRIETARY/CONFIDENTIAL.  Use is subject to license terms.
	 */
?>
<?
	class WorldPay extends PSP
	{
		function Checkout(&$params)
		{
			$this->smarty->assign("params",$params);
			$this->smarty->display($this->config["template"]."/payment/worldpay/checkoutform.tpl.php");
		}

		function Callback(&$params)
		{
			$valid=true;
	        $remote=$REMOTE_ADDR;
	        for($i=0;$i<256;$i++)
	        {
	                $ra="155.136.68.".$i;
	                if($remote==$ra)
	                        $valid=true;
	        }
	        for($i=0;$i<256;$i++)
	        {
	                $ra="193.41.220.".$i;
	                if($remote==$ra)
	                        $valid=true;
	        }
	        for($i=0;$i<256;$i++)
	        {
	                $ra="193.41.221.".$i;
	                if($remote==$ra)
	                        $valid=true;
	        }
	        if($params["transStatus"]=="Y")
	                $paid=true;
	        else
	                $paid=false;
	        if($params["testMode"]>0)
	                $paid=false;
			if($valid && $paid)
			{
				$order["session_id"]=$params["cartId"];
				$order["time"]=time();
				$order["name"]=$params["name"];
		 		$order["address"]=$params["address"];
		 		$order["postcode"]=$params["postcode"];
		 		$order["country"]=$params["country"];
		 		$order["email"]=$params["email"];
				$order["tel"]=$params["MC_tel"];
		 		$order["total"]=$params["MC_total"];
		 		$order["shipping"]=$params["MC_shipping"];
		 		$order["paid"]=$params["authAmount"];
				$order["affiliate_id"]=$params["MC_affiliate_id"];
				$order["txnvars"]="";
				$order["status"]="finished";
				$this->Finished($order);
			}
			else if($valid && !$paid)
			{
				$order["status"]="cancelled";
				$this->Cancelled();
			}
			else
			{
				$order["status"]="invalid";
				$this->Invalid();
			}
			$order["redirect"]=false;
			return $order;
		}
	}
?>