<?
	class SecureHosting extends PSP
	{
		function Checkout(&$params)
		{
		}

		function Details(&$params)
		{
			$params['vars']['shippingcharge']=number_format($params["vars"]["shipping"]+$params['vars']['packing'],2,".","");
			$params['vars']['transactiontax']=0;
			$params['vars']['transactionamount']=number_format($params["vars"]["total"]+$params["vars"]["shipping"]+$params['vars']['packing'],2,".","");
			$params['mobile'] = MOBILE_DEV?'mobile':'';
		
			$this->smarty->assign("params",$params);
			$this->smarty->display($this->config['template']."/payment/securehosting/details.tpl.php");
		}
		
		function Finished(&$params)
		{
			//$this->smarty->assign("params",$params);
			//$this->smarty->display($this->config['template']."/payment/securehosting/finished.tpl.php");
		}

		function CheckComplete(&$params)
		{
		}

		function SessionID(&$params)
		{
			if(isset($params['custom']))
				return $params['custom'];
			else
				return false;
		}

		function Callback(&$params)
		{
			if($params['request']['transactionnumber']+0 > -1
				&& $_SERVER['HTTP_REFERER'] === 'https://www.secure-server-hosting.com/secutran/ProcessCallbacks.php'
				&& check_txnvar("txn_id",$params['request']['transactionnumber'])==false)
				{
					//Finish up order here
					$order['time']=time();
					$order['valid']=1;
					$order['name']=$params['vars']['billing_name'];
	 				$order['address']=$params['vars']['billing_line1']."\n".$params['vars']['billing_line2']."\n".$params['vars']['billing_line3']."\n".$params['vars']['billing_line4'];
			 		$order['postcode']=$params['vars']['billing_postcode'];
			 		$order['country']=$params['vars']['billing_country'];
					$order['tel']=$params['vars']['billing_phone'];
			 		$order['email']=$params['vars']['billing_email'];
					
					$order['delivery_name']=$params['vars']['delivery_name'];
	 				$order['delivery_address']=$params['vars']['delivery_line1']."\n".$params['vars']['delivery_line2']."\n".$params['vars']['delivery_line3']."\n".$params['vars']['delivery_line4'];
			 		$order['delivery_postcode']=$params['vars']['delivery_postcode'];
			 		$order['delivery_country']=$params['vars']['delivery_country'];
			 		$order['delivery_email']=$params['vars']['delivery_email'];
			 		$order['delivery_phone']=$params['vars']['delivery_phone'];

					$order['txnvars']['txn_id']=$params['request']['transactionnumber'];
					$order['txnvars']['name']=$params['request']['name'];
					$order['txnvars']['address']=$params['request']['street']."\n".$params['request']['city']."\n".$params['request']['state'];
					$order['txnvars']['postcode']=$params['request']['postcode'];
					$order['txnvars']['country']=$params['request']['country'];
					$order['txnvars']['tel']=$params['request']['tel'];
					$order['txnvars']['email']=$params['request']['email'];
					$order['txnvars']['cv2']=$params['request']['cv2'];

					$order['paid']=$params['request']['amount'];
					$order['status']="finished";
					$order['redirect']=false;
					echo 'success';
				}
				else
				{
					$order['status']="invalid";
					$order['redirect']=false;
				}
			return $order;
		}

		function Refund(&$params)
		{
			$xmldoc = '<?xml version="1.0"?>';
			$xmldoc .= '<request>';
			$xmldoc .= '<type>refund</type>';
			$xmldoc .= '<authentication>';
			$xmldoc .= '<shreference>'.$this->config['psp']['shreference'].'</shreference>';
			$xmldoc .= '<password>'.$this->config['psp']['password'].'</password>';
			$xmldoc .= '</authentication>';
			$xmldoc .= '<transaction>';
			$xmldoc .= '<reference>'.$params['reference'].'</reference>';
			$xmldoc .= '<amount>'.($params['amount']+0).'</amount>';
			$xmldoc .= '</transaction>';
			$xmldoc .= '</request>';
				
			$post_vars = "xmldoc=".urlencode($xmldoc);
			$ch = curl_init();
			curl_setopt ($ch, CURLOPT_URL, "https://www.secure-server-hosting.com/secutran/api.php");
			curl_setopt ($ch, CURLOPT_POST, 1);
			curl_setopt ($ch, CURLOPT_POSTFIELDS, $post_vars);
			curl_setopt ($ch, CURLOPT_HEADER, 0);
			curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt ($ch, CURLOPT_TIMEOUT, 15);
			$xml_text = trim(curl_exec ($ch));
			
			$xml = simplexml_load_string($xml_text);
			if($xml && strtoupper(trim($xml->status)) == 'OK')
				return true;
			else
				return false;
		}
		
		function AdditionalPayment(&$params, $settings)
		{
			$params['vars']['shippingcharge']=number_format($params["vars"]["shipping"]+$params['vars']['packing'],2,".","");
			$params['vars']['transactiontax']=0;
			$params['vars']['transactionamount']=number_format($params["vars"]["total"]+$params["vars"]["shipping"]+$params['vars']['packing'],2,".","");
			
			$xmldoc = '<?xml version="1.0"?>';
			$xmldoc .= '<request>';
			$xmldoc .= '<type>additional</type>';
			$xmldoc .= '<authentication>';
			$xmldoc .= '<shreference>'.$this->config['psp']['shreference'].'</shreference>';
			$xmldoc .= '<checkcode>'.$this->config['psp']['checkcode'].'</checkcode>';
			$xmldoc .= '</authentication>';
			$xmldoc .= '<transaction>';
			$xmldoc .= '<reference>'.$settings['reference'].'</reference>';
			$xmldoc .= '<currency>GBP</currency>';
			$xmldoc .= '<transactionamount>'.number_format($params['vars']['transactionamount']+0, 2, '.', '').'</transactionamount>';
			$xmldoc .= '<transactiontax>'.number_format($params['vars']['transactiontax']+0, 2, '.', '').'</transactiontax>';
			$xmldoc .= '<shippingcharge>'.number_format($params['vars']['shippingcharge']+0, 2, '.', '').'</shippingcharge>';
			$xmldoc .= '<secuitems><![CDATA['.'[||'.$this->config['psp']['item_name'].'|'.number_format($params['vars']['transactionamount']+0, 2, '.', '').'|1|'.number_format($params['vars']['transactionamount']+0, 2, '.', '').']'.']]></secuitems>';
			$xmldoc .= '<cardholdersname>'.$params["billing"]["name"].'</cardholdersname>';
			$xmldoc .= '<cardholdersemail>'.$params["billing"]["email"].'</cardholdersemail>';
			$xmldoc .= '<cardholdertelephonenumber>'.$params["billing"]["phone"].'</cardholdertelephonenumber>';
			$xmldoc .= '<cardholderaddr1>'.$params["billing"]["line1"].' '.$params["billing"]["line2"].'</cardholderaddr1>';
			$xmldoc .= '<cardholderaddr2></cardholderaddr2>';
			$xmldoc .= '<cardholdercity>'.$params["billing"]["line3"].'</cardholdercity>';
			$xmldoc .= '<cardholderstate>'.$params["billing"]["line4"].'</cardholderstate>';
			$xmldoc .= '<cardholderpostcode>'.$params["billing"]["postcode"].'</cardholderpostcode>';
			$xmldoc .= '<cardholdercountry>'.$params["billing"]["country"].'</cardholdercountry>';
			$xmldoc .= '<cv2>'.$settings['cv2'].'</cv2>';
			$xmldoc .= '</transaction>';
			$xmldoc .= '<callback>';
			$xmldoc .= '<callbackurl>'.$this->config['protocol'].$this->config['url'].$this->config['dir'].'callback</callbackurl>';
			$xmldoc .= '<callbackdata><![CDATA['.'custom|'.$params["session_id"].'|name|#cardholdersname|email|#cardholdersemail|amount|#transactionamount|street|#cardholderaddr1|city|#cardholdercity|state|#cardholderstate|country|#cardholdercountry|postcode|#cardholderpostcode|tel|#cardholdertelephonenumber|cvs|#cvs'.']]></callbackdata>';
			$xmldoc .= '</callback>';
			$xmldoc .= '</request>';
			
			$post_vars = "xmldoc=".urlencode($xmldoc);
			$ch = curl_init();
			curl_setopt ($ch, CURLOPT_URL, $this->config['psp']['url']."api.php");
			curl_setopt ($ch, CURLOPT_POST, 1);
			curl_setopt ($ch, CURLOPT_POSTFIELDS, $post_vars);
			curl_setopt ($ch, CURLOPT_HEADER, 0);
			curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt ($ch, CURLOPT_TIMEOUT, 15);
			$xml_text = trim(curl_exec ($ch));
			
			//var_export($xmldoc);
			//var_export($xml_text);exit;
			
			$xml = simplexml_load_string($xml_text);
			if($xml && strtoupper(trim($xml->status)) == 'OK')
				return true;
			else
				return false;
		}
		
		function GetTxnVars(&$params)
		{
			return $params;
		}
	}
?>