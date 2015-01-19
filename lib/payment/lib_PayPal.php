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
	class PayPal extends PSP
	{
		function Checkout(&$params)
		{
			$this->smarty->assign("params",$params);
			$this->smarty->display($this->config['template']."/payment/paypal/checkout.tpl.php");
		}

		function Details(&$params)
		{
			$this->smarty->assign("params",$params);
			$this->smarty->display($this->config['template']."/payment/paypal/details.tpl.php");
		}

		function CheckComplete(&$params)
		{

		}

		function SessionID(&$params)
		{
			return $params['custom'];
		}

		function Callback(&$params)
		{
			$data="cmd=".urlencode("_notify-validate");
			$keys=array_keys($params['request']);
			foreach($keys as $key)
			{
				if($key!="fuseaction" && $key!="session_id")
					if(trim($params['request'][$key])!="")
						$data.="&".urlencode($key)."=".urlencode($params['request'][$key]);
			}
			//Postit
			$ch=curl_init("https://www.".(($this->config['psp']['test_mode']) ? "sandbox." : "")."paypal.com/cgi-bin/webscr");
			//curl_setopt($ch,CURLOPT_CAINFO,"c:\\php\\curl\\curl-ca-bundle.crt");
			curl_setopt($ch,CURLOPT_POST,1);
			curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
			curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,0);
			ob_start();
			curl_exec($ch);
			$verify=ob_get_contents();
			ob_end_clean();
			curl_close($ch);

			//Check payment verified
			if(trim($verify)=="VERIFIED"
				&& $params['request']['payment_status']=="Completed"
				&& $params['request']['business']==$this->config['psp']['business']
				&& check_txnvar("txn_id",$params['txn_id'])==false
				&& $params['request']['mc_gross']==($params['vars']['total']+$params['vars']['packing']+$params['vars']['shipping']))
				{
					//Finish up order here
					$order['time']=time();
					$order['valid']=1;
					$order['name']=$params['request']['first_name']." ".$params['request']['last_name'];
	 				$order['delivery_name']=$params['request']['first_name']." ".$params['request']['last_name'];

		 			$order['address']=$params['request']['address_street']."\n".$params['request']['address_city']."\n".$params['request']['address_state'];
			 		$order['postcode']=$params['request']['address_zip'];
			 		$order['country']=$params['request']['address_country'];
		 			$order['delivery_name']=$params['request']['address_street']."\n".$params['request']['address_city']."\n".$params['request']['address_state'];
			 		$order['delivery_address']=$params['request']['address_zip'];
			 		$order['delivery_postcode']=$params['request']['address_country'];
					$order['delivery_country']=$params['request']['address_country'];

			 		$order['email']=$params['request']['payer_email'];

					$order['txnvars']['txn_id']=$params['request']['txn_id'];

					$order['paid']=$params['request']['mc_gross'];
					$order['status']="finished";
					$order['redirect']=false;
				}
			return $order;
		}

		function GetTxnVars(&$params)
		{
			return $params;
		}
	}
?>