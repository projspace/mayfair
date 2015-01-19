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
	include("../lib/payment/local/lib_CreditCard.php");

	class Local extends PSP
	{
		function Checkout(&$params)
		{
			$this->smarty->assign("params",$params);
			$this->smarty->display($this->config['template']."/payment/local/checkout.tpl.php");
		}

		function Details(&$params)
		{
			$this->smarty->assign("params",$params);
			$this->smarty->display($this->config['template']."/payment/local/details.tpl.php");
		}

		function CheckComplete(&$params)
		{
			$message=array();
			if(trim($params['request']['name'])=="")
				array_push($message,"Cardholders Name not entered");
			if(trim($params['request']['address'])=="")
				array_push($message,"Billing Address not entered");
			if(trim($params['request']['postcode'])=="")
				array_push($message,"Billing Postcode not entered");
			if(trim($params['request']['country'])=="")
				array_push($message,"Billing Country not entered");
			if(trim($params['request']['deliver_billing'])!="on")
			{
				if(trim($params['request']['delivery_name'])=="")
					array_push($message,"Delivery Name not entered");
				if(trim($params['request']['delivery_address'])=="")
					array_push($message,"Delivery Address not entered");
				if(trim($params['request']['delivery_postcode'])=="")
					array_push($message,"Delivery Postcode not entered");
				if(trim($params['request']['delivery_country'])=="")
					array_push($message,"Delivery Country not entered");
			}
			if(trim($params['request']['email'])=="")
				array_push($message,"Email Address not entered");
			if(!ereg("[a-zA-Z._0-9]@[a-zA-Z0-9_.-]",$params['request']['email']))
				array_push($message,"Email Address entered in incorrect format");
			if(trim($params['request']['card_type'])=="")
				array_push($message,"You must select your card type");
			if(trim($params['request']['card_no'])=="")
				array_push($message,"Card Number not entered");
			if(trim($params['request']['card_cv2'])=="")
				array_push($message,"CV2 not entered");
			if(trim($params['request']['card_end'])=="")
				array_push($message,"Expiry Date not entered");
			if(trim($params['request']['card_issue'])=="" && $params['request']['card_type']=="Switch")
				array_push($message,"Issue Number not entered");

			$cc=new CreditCard();
			$ccresult=$cc->check($params['request']['card_no'],$params['request']['card_start'],$params['request']['card_end']);

			if($params['request']['terms']!="on")
				array_push($message,"You must accept our Terms and Conditions of sale");

			return $message;
		}

		function SaveDetails(&$params)
		{
			$details['txnvars']['name']=$params['request']['name'];
	 		$details['txnvars']['address']=$params['request']['address'];
	 		$details['txnvars']['postcode']=$params['request']['postcode'];
	 		$details['txnvars']['country']=$params['request']['country'];

	 		if($params['request']['deliver_billing']=="on")
	 		{
 	 			$details['txnvars']['delivery_name']=$params['request']['name'];
				$details['txnvars']['delivery_address']=$params['request']['address'];
				$details['txnvars']['delivery_postcode']=$params['request']['postcode'];
			}
			else
			{
				$details['txnvars']['delivery_name']=$params['request']['name'];
				$details['txnvars']['delivery_address']=$params['request']['address'];
				$details['txnvars']['delivery_postcode']=$params['request']['postcode'];
			}
			$details['txnvars']['delivery_country']=$params['request']['delivery_country'];

	 		$details['txnvars']['email']=$params['request']['email'];
			$details['txnvars']['tel']=$params['request']['tel'];

			$details['txnvars']['Card Type']=$params['request']['card_type']^$this->config['psp']['key'];
			$details['txnvars']['Card Number']=$params['request']['card_no']^$this->config['psp']['key'];
			$details['txnvars']['Card CV2']=$params['request']['card_cv2']^$this->config['psp']['key'];
			$details['txnvars']['Card Valid From']=$params['request']['card_start']^$this->config['psp']['key'];
			$details['txnvars']['Card Expiry']=$params['request']['card_end']^$this->config['psp']['key'];
			$details['txnvars']['Card Issue No']=$params['request']['card_issue']^$this->config['psp']['key'];

			$details['redirect']=true;
			$details['redirect_url']=$this->config['protocol'].$this->config['url'].$this->config['dir']."index.php?fuseaction=shop.callback";

			return $details;
		}

		function Callback(&$params)
		{
			$order['time']=time();
			$order['valid']=1;
			$order['name']=$params['txnvars']['name'];
	 		$order['address']=$params['txnvars']['address'];
	 		$order['postcode']=$params['txnvars']['postcode'];
	 		$order['country']=$params['txnvars']['country'];

 			$order['delivery_name']=$params['txnvars']['name'];
			$order['delivery_address']=$params['txnvars']['address'];
			$order['delivery_postcode']=$params['txnvars']['postcode'];
			$order['delivery_country']=$params['txnvars']['delivery_country'];

	 		$order['email']=$params['txnvars']['email'];
			$order['tel']=$params['txnvars']['tel'];

			$order['txnvars']['Card Type']=$params['txnvars']['Card Type'];
			$order['txnvars']['Card Number']=$params['txnvars']['Card Number'];
			$order['txnvars']['Card CV2']=$params['txnvars']['Card CV2'];
			$order['txnvars']['Card Valid From']=$params['txnvars']['Card Valid From'];
			$order['txnvars']['Card Expiry']=$params['txnvars']['Card Expiry'];
			$order['txnvars']['Card Issue No']=$params['txnvars']['Card Issue No'];

			$order['paid']=$params['vars']['total']+$params['vars']['shipping']+$params['vars']['packing'];
			$order['status']="finished";
			$order['redirect']=true;

			return $order;
		}

		function GetTxnVars(&$params)
		{
			$txnvars['Card Type']=$params['Card Type']^$this->config['psp']['key'];
			$txnvars['Card Number']=$params['Card Number']^$this->config['psp']['key'];
			$txnvars['Card CV2']=$params['Card CV2']^$this->config['psp']['key'];
			$txnvars['Card Valid From']=$params['Card Valid From']^$this->config['psp']['key'];
			$txnvars['Card Expiry']=$params['Card Expiry']^$this->config['psp']['key'];
			$txnvars['Card Issue No']=$params['Card Issue No']^$this->config['psp']['key'];
			return $txnvars;
		}
	}
?>
