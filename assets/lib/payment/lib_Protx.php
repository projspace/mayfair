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
	class Protx extends PSP
	{
		/**
		* Send the formatted post to protx
		*/
		function _post($url,$post)
		{
			$r=curl_init();
			curl_setopt($r,CURLOPT_SSL_VERIFYPEER,0);
			curl_setopt($r,CURLOPT_POST,true);
			curl_setopt($r,CURLOPT_URL,$url);
			curl_setopt($r,CURLOPT_POSTFIELDS,$post);
			ob_start();
			$succ=curl_exec($r);
			$output=ob_get_contents();
			ob_end_clean();
			echo curl_error($r);
			curl_close($r);
			return $output;
		}

		/**
		* Format the post data
		*/
		function _makePost($vals)
		{
			$keys=array_keys($vals);
			$post="";
			foreach($keys as $key)
			{
				if($post!="")
					$post.="&";
				$post.=urlencode($key)."=".urlencode($vals[$key]);
			}
			return $post;
		}
		
		function Checkout($params)
		{
			$this->smarty->assign("params",$params);
			$this->smarty->display($this->config["template"]."/payment/protx/checkout.tpl.php");
		}
		
		function Details($params)
		{
			$this->smarty->assign("params",$params);
			$this->smarty->display($this->config["template"]."/payment/protx/details.tpl.php");
		}
		
		function SaveDetails($params)
		{
			$details=$this->db->Execute(
				sprintf("
					SELECT
						id
					FROM
						shop_sessions
					WHERE
						session_id=%s
				"
					,$this->db->Quote($params['session_id'])
				)
			);
			echo $this->db->ErrorMsg();
		
			$VendorTxCode=time().".".$details->fields['id'];
		
			$url="https://ukvps.protx.com/vps200/dotransaction.dll?Service=VendorRegisterTx";
			if($this->config['psp']['testmode'])
				$url="https://ukvpstest.protx.com/vps200/dotransaction.dll?Service=VendorRegisterTx";
			else if($this->config['psp']['simulator'])
				$url="https://ukvpstest.protx.com/VSPSimulator/VSPServerGateway.asp?Service=VendorRegisterTx";
			$post["VPSProtocol"]="2.22";
			$post["TxType"]="PAYMENT";
			$post["Vendor"]=$this->config["psp"]["vendor"];
			$post["VendorTxCode"]=$VendorTxCode;
			$post["Amount"]=number_format($params["vars"]["total"]+$params["vars"]["shipping"]+$params['vars']['packing'],2,".","");
			$post["Currency"]=$this->config["psp"]["currency"];
			$post["Description"]=$this->config["psp"]["description"];
			$post["NotificationURL"]=$this->config['protocol'].$this->config['url'].$this->config['dir']."index.php?fuseaction=shop.callback";
		
			$post["BillingAddress"]=$params["request"]["address"];
			$post["BillingPostCode"]=$params["request"]["postcode"];
		
			if($params['request']['deliver_billing']!="on")
			{
				$post["DeliveryAddress"]=$params['request']['deliveryaddress'];
				$post["DeliveryPostCode"]=$params['request']['deliverypostcode'];
			}
		
			$post['CustomerName']=$params['request']['name'];
			$post['ContactNumber']=$params['request']['tel'];
			$post['CustomerEMail']=$params['request']['email'];
			$output=$this->_post($url,$this->_makePost($post));
		
			$temp=explode("\n",$output);
		
			$fp=fopen("/home/httpd/vhosts/wickersgiftbaskets.com/httpdocs/debug.txt","a+");
		
			for($i=0;$i<count($temp);$i++)
			{
				$key=trim(ereg_replace("^([a-zA-Z]+)=(.*)$","\\1",$temp[$i]));
				$value=trim(ereg_replace("^([a-zA-Z]+)=(.*)$","\\2",$temp[$i]));
				$result[$key]=$value;
			}
		
			fwrite($fp,print_r($result,true)."\n\n");
		
			$ret=array();
		
			if($result["Status"]=="OK")
			{
				$ret['txnvars']['name']=$params['request']['name'];
				$ret['txnvars']['address']=$params['request']['address'];
				$ret['txnvars']['postcode']=$params['request']['postcode'];
				$ret['txnvars']['country']=$params['request']['country'];
		
				if($params['request']['deliver_billing']=="on")
				{
					$ret['txnvars']['delivery_name']=$params['request']['name'];
					$ret['txnvars']['delivery_address']=$params['request']['address'];
					$ret['txnvars']['delivery_postcode']=$params['request']['postcode'];
				}
				else
				{
					$ret['txnvars']['delivery_name']=$params['request']['delivery_name'];
					$ret['txnvars']['delivery_address']=$params['request']['delivery_address'];
					$ret['txnvars']['delivery_postcode']=$params['request']['delivery_postcode'];
				}
				$ret['txnvars']['delivery_country']=$params['request']['delivery_country'];

				$ret['txnvars']['instructions']=$params['request']['instructions'];
				$ret['txnvars']['message']=$params['request']['message'];	
	
				$ret['txnvars']['email']=$params['request']['email'];
				$ret['txnvars']['tel']=$params['request']['tel'];
		
				$ret['txnvars']['VendorTxCode']=$VendorTxCode;
				$ret['txnvars']['VPSTxId']=$result["VPSTxId"];
				$ret['txnvars']['SecurityKey']=$result["SecurityKey"];
		
				$ret['redirect']=true;
				$ret['redirect_url']=$result['NextURL'];
			}
			else
			{
				print "<h3>Error with Payment Service Provider</h3>"
					."<p>There has been a problem sending your order to the company that deals with taking payment from our customers.</p>"
					."<p>If you try hitting your browsers refresh (or reload button) you may be redirected to our payment system.</p>"
					."<p>If after refreshing you get this message again please either phone us or email us on <a href=\"mailto:sales@\">sales@</a> to discuss making an order.</p>"
					."<p>We apologise for any inconveniance caused,</p>"
					."<p>".$this->config['company']."<hr>";
				$keys=array_keys($result);
				for($i=0;$i<count($keys);$i++)
					print "<br>".$keys[$i].":".$result[$keys[$i]]."<br>";
			}
			fwrite($fp,print_r($ret,true)."\n\n----------------------------------------\n\n");
			fclose($fp);
			return $ret;
		}
		
		function SessionID(&$params)
		{
			$code=$params['VendorTxCode'];
			$id=substr($code,strpos($code,".")+1);
			$session=$this->db->Execute(
				sprintf("
					SELECT
						session_id
					FROM
						shop_sessions
					WHERE
						id=%u
				"
					,$id
				)
			);
			return $session->fields['session_id'];
		}
		
		function Callback($params)
		{
			if($params['request']['Status']=="OK")
			{
				$fp=fopen("/home/httpd/vhosts/wickersgiftbaskets.com/httpdocs/debug.txt","a+");
				fwrite($fp,"CALLBACK:\n".print_r($params,true)."\n-------------------------------------\n");
				//$sessionid
				$session=$this->db->Execute(
					sprintf("
						SELECT
							shop_sessions.id
						FROM
							shop_sessions
							,shop_session_txnvars
						WHERE
							shop_session_txnvars.session_id=shop_sessions.id
						AND
							shop_session_txnvars.name=%s
						AND
							shop_session_txnvars.value=%s
					"
						,$this->db->Quote('VendorTxCode')
						,$this->db->Quote($params['request']['VendorTxCode'])
					)
				);
				$rows=$this->db->Execute(
					sprintf("
						SELECT
							name
							,value
						FROM
							shop_session_txnvars
						WHERE
							session_id=%u
					"
						,$session->fields['id']
					)
				);
				$txnvars=array();
				while($row=$rows->FetchRow())
					$txnvars[$row['name']]=$row['value'];
			
				fwrite($fp,$session->RecordCount()." ORDERS IN DB\n\n");
				fwrite($fp,print_r($params,true)."\n\n");
				if($session->RecordCount()>0)
				{
					if(ereg("^OK",$params['request']["Status"]))
					{
						$data["VPSTxId"]=$params['request']["VPSTxId"];
						$data["VendorTxCode"]=$params['request']["VendorTxCode"];
						$data["Status"]=$params['request']["Status"];
						$data["TxAuthNo"]=$params['request']['TxAuthNo'];
						$data["Vendor"]=$this->config['psp']['vendor'];
						$data["AVSCV2"]=$params['request']["AVSCV2"];
						$data["SecurityKey"]=$txnvars["SecurityKey"];
						$data["AddressResult"]=$params['request']['AddressResult'];
						$data["PostCodeResult"]=$params['request']['PostCodeResult'];
						$data["CV2Result"]=$params['request']['CV2Result'];
						$data["GiftAid"]=$params['request']['GiftAid'];
						$data["3DSecureStatus"]=$params['request']['3DSecureStatus'];
						$data["CAVV"]=$params['request']['CAVV'];
						
						fwrite($fp,"DATA:".print_r($data,true)."\n\n".join("",$data)."\n\n");
						$hash=strtoupper(md5(join("",$data)));
						
						fwrite($fp,"hash=".$hash."\n");
						fwrite($fp,"sign=".$params['request']["VPSSignature"]."\n");
						
						if($hash==$params['request']["VPSSignature"])
							$callback=true;
						else
							$callback=false;
							
						if($callback)
						{
							$order['time']=time();
							$order['valid']=1;
							$order['name']=$params['txnvars']['name'];
							$order['address']=$params['txnvars']['address'];
							$order['postcode']=$params['txnvars']['postcode'];
							$order['country']=$params['txnvars']['country'];
							
							$order['delivery_name']=$params['txnvars']['delivery_name'];
							$order['delivery_address']=$params['txnvars']['delivery_address'];
							$order['delivery_postcode']=$params['txnvars']['delivery_postcode'];
							$order['delivery_country']=$params['txnvars']['delivery_country'];
					
							$order['email']=$params['txnvars']['email'];
							$order['tel']=$params['txnvars']['tel'];
							
							$order['txnvars']['VendorTxCode']=$params['txnvars']['VendorTxCode'];
							$order['txnvars']['VPSTxId']=$params['txnvars']['VPSTxId'];
							$order['txnvars']['SecurityKey']=$params['txnvars']['SecurityKey'];
							$order['txnvars']['TxAuthNo']=$params['request']['TxAuthNo'];
							$order['txnvars']['message']=$params['txnvars']['message'];
							$order['txnvars']['instructions']=$params['txnvars']['instructions'];
					
							$order['paid']=$params['vars']['total']+$params['vars']['shipping']+$params['vars']['packing'];
							$order['status']="finished";
							$order['redirect']=false;
							$order['ptstatus']="OK";
							$order['act']="finished";
						}
						else
						{
							$order["ptstatus"]="INVALID";
							$order["act"]="invalid";
						}
					}
					else
					{
						$order["ptstatus"]="OK";
						$order["act"]="cancelled";
					}
				}
				else
				{
					$order["ptstatus"]="OK";
					$order["act"]="invalid";
				}
				fclose($fp);
			}
			else
			{
				$order["ptstatus"]="INVALID";
				$order["act"]="invalid";
			}
			$order["redirect"]==false;
			print "Status=".$order["ptstatus"]."\r\n";
			print "RedirectURL=".$this->config['protocol'].$this->config['url'].$this->config['dir']."index.php?fuseaction=shop.".$order["act"]."\r\n";
			return $order;
		}
		
		function Refund(&$params)
		{
			$get=$this->db->Execute(
				sprintf("
					SELECT
						*
					FROM
						shop_order_txnvars
					WHERE
						order_id=%u
				"
					,$params['order_id']
				)
			);
			$txnvars=array();
			while($row=$get->FetchRow())
			{
				$txnvars[$row['name']]=$row['value'];			
			}			
			$VendorTxCode="REFUND".$params['amount'].".".$params['order_id'];
		
			$url="https://ukvps.protx.com/vps200/dotransaction.dll?Service=VendorRefundTx";

			if($this->config['psp']['testmode'])
				$url="https://ukvpstest.protx.com/vps200/dotransaction.dll?Service=VendorRefundTx";
			else if($this->config['psp']['simulator'])
				$url="https://ukvpstest.protx.com/VSPSimulator/VSPServerGateway.asp?Service=VendorRefundTx";

			$post["VPSProtocol"]="2.22";
			$post["TxType"]="REFUND";
			$post["Vendor"]=$this->config["psp"]["vendor"];
			$post["VendorTxCode"]=$VendorTxCode;
			$post["Amount"]=number_format($params['amount'],2,".","");
			$post["Currency"]=$this->config["psp"]["currency"];
			$post["Description"]=$params['description'];
			$post['RelatedVPSTxId']=$txnvars['VPSTxId'];
			$post['RelatedVendorTxCode']=$txnvars['VendorTxCode'];
			$post['RelatedSecurityKey']=$txnvars['SecurityKey'];
			$post['RelatedTxAuthNo']=$txnvars['TxAuthNo'];
			
			
			$output=$this->_post($url,$this->_makePost($post));
		
			$temp=explode("\n",$output);
		
			for($i=0;$i<count($temp);$i++)
			{
				$key=trim(ereg_replace("^([a-zA-Z]+)=(.*)$","\\1",$temp[$i]));
				$value=trim(ereg_replace("^([a-zA-Z]+)=(.*)$","\\2",$temp[$i]));
				$result[$key]=$value;
			}
		
		
			$ret=array();
		
			if($result["Status"]=="OK")
			{
				$ret['txnvars']['name']=$params['request']['name'];
				$ret['txnvars']['address']=$params['request']['address'];
				$ret['txnvars']['postcode']=$params['request']['postcode'];
				$ret['txnvars']['country']=$params['request']['country'];
		
				if($params['request']['deliver_billing']=="on")
				{
					$ret['txnvars']['delivery_name']=$params['request']['name'];
					$ret['txnvars']['delivery_address']=$params['request']['address'];
					$ret['txnvars']['delivery_postcode']=$params['request']['postcode'];
				}
				else
				{
					$ret['txnvars']['delivery_name']=$params['request']['delivery_name'];
					$ret['txnvars']['delivery_address']=$params['request']['delivery_address'];
					$ret['txnvars']['delivery_postcode']=$params['request']['delivery_postcode'];
				}
				$ret['txnvars']['delivery_country']=$params['request']['delivery_country'];
		
				$ret['txnvars']['email']=$params['request']['email'];
				$ret['txnvars']['tel']=$params['request']['tel'];
		
				$ret['txnvars']['VendorTxCode']=$VendorTxCode;
				$ret['txnvars']['VPSTxId']=$result["VPSTxId"];
				$ret['txnvars']['SecurityKey']=$result["SecurityKey"];
		
				$ret['redirect']=true;
				$ret['redirect_url']=$result['NextURL'];
			}
			else
			{
				print "<h3>Error with Payment Service Provider</h3>"
					."<p>There has been a problem sending your order to the company that deals with taking payment from our customers.</p>"
					."<p>If you try hitting your browsers refresh (or reload button) you may be redirected to our payment system.</p>"
					."<p>If after refreshing you get this message again please either phone us or email us on <a href=\"mailto:sales@\">sales@</a> to discuss making an order.</p>"
					."<p>We apologise for any inconveniance caused,</p>"
					."<p>".$this->config['company']."<hr>";
				$keys=array_keys($result);
				for($i=0;$i<count($keys);$i++)
					print "<br>".$keys[$i].":".$result[$keys[$i]]."<br>";
			}
			return $ret;
		}

		function GetTxnVars(&$params)
		{
			return $params;
		}
	}
?>
