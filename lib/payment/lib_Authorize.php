<?
	include dirname(__FILE__)."/authorize/AuthorizeNet.php";
	include dirname(__FILE__)."/lib_WsAuthorizeNetCim.php";
	if(!defined('AUTHORIZENET_SANDBOX'))
		define('AUTHORIZENET_SANDBOX', $config['psp']['test_mode']);

	class Authorize extends PSP
	{
		function Checkout(&$params)
		{
		}
		
		function Details(&$params)
		{
            global $elems;

			$params['vars']['shippingcharge']=number_format($params["vars"]["shipping"]+$params['vars']['packing'],2,".","");
			$params['vars']['transactiontax']=0;
			$params['vars']['transactionamount']=number_format($params["vars"]["total"]+$params["vars"]["shipping"]+$params['vars']['packing']+$params['vars']['tax'],2,".","");
			
		
			$params['vars']['fp_timestamp'] = time();
			$params['vars']['fingerprint'] = AuthorizeNetSIM_Form::getFingerprint(
													$this->config['psp']['api_login_id'],
  													$this->config['psp']['transaction_key'], 
  													$params['vars']['transactionamount'], 
  													$params['session_id'], 
  													$params['vars']['fp_timestamp']
  											);
			
			$tmp_name = explode ( " ", $params['billing']['name'],2 );
			$params['billing']['first_name'] 	=	$tmp_name[0];
			$params['billing']['last_name'] 	=	$tmp_name[1];
			
			$tmp_name = explode ( " ", $params['delivery']['name'],2 );
			$params['delivery']['first_name'] 	=	$tmp_name[0];
			$params['delivery']['last_name'] 	=	$tmp_name[1];
			
			$this->smarty->assign("params",$params);
			$this->smarty->display($this->config['template']."/payment/authorize/details.tpl.php");

            $elems->placeholder('script')->addContent('
            <script language="javascript" type="text/javascript">
            /* <![CDATA[ */
                //_gaq.push(function() {
                    $(document).ready(function(){
                        //var pageTracker = _gat._getTrackerByName();
                        //var linkerUrl = pageTracker._getLinkerUrl($(\'#frmPayment\').attr(\'action\'));
                        var linkerUrl = $(\'#frmPayment\').attr(\'action\');
                        $(\'#frmPayment\').attr(\'action\', linkerUrl);
                        $(\'#frmPayment\').submit();
                    });
                //});
            /* ]]> */
            </script>
            ');
			
		}
		
		function SessionID(&$params)
		{
			if(isset($params['x_po_num']))
				return $params['x_po_num'];
			else
				return false;
		}
		
		
		function Callback(&$params)
		{
			if($params['request']['x_response_code'] == 1
				//&& $_SERVER['HTTP_REFERER'] === 'https://www.secure-server-hosting.com/secutran/ProcessCallbacks.php'
				&& (($this->config['psp']['test_mode'] || $params['request']['x_test_request'] == 'true')?true:check_txnvar("txn_id",$params['request']['x_trans_id'])==false))
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

					$order['txnvars']['txn_id']=$params['request']['x_trans_id'];
					$order['txnvars']['name']=$params['request']['x_first_name']." ".$params['request']['x_last_name'];
					$order['txnvars']['address']=$params['request']['x_address']."\n".$params['request']['x_city']."\n".$params['request']['x_state'];
					$order['txnvars']['postcode']=$params['request']['x_zip'];
					$order['txnvars']['country']=$params['request']['x_country'];
					$order['txnvars']['tel']=$params['request']['x_phone'];
					$order['txnvars']['email']=$params['request']['x_email'];
					$order['txnvars']['cv2']=$params['request']['cv2'];
					$order['txnvars']['auth_code']=$params['request']['x_auth_code'];
					$order['txnvars']['account_number']=$params['request']['x_account_number'];
					
					if($params['txnvars']['customerProfileId'])
					$order['txnvars']['customerProfileId'] = $params['txnvars']['customerProfileId'];
					if($params['txnvars']['customerAddressId'])
					$order['txnvars']['customerAddressId'] = $params['txnvars']['customerAddressId'];
					if($params['txnvars']['paymentProfileId'])
					$order['txnvars']['paymentProfileId'] = $params['txnvars']['paymentProfileId'];
					
					
					$order['paid']=$params['request']['x_amount'];
					$order['status']="finished";
					$order['redirect']=true;
					
				}
				else
				{
					$order['status']="invalid";
					$order['redirect']=false;
				}
			return $order;
		}
		
		
		function CallbackCIM(&$params) {
			if($params['transactionResponse']->response_code == 1
				//&& $_SERVER['HTTP_REFERER'] === 'https://www.secure-server-hosting.com/secutran/ProcessCallbacks.php'
				&& ($this->config['psp']['test_mode']?true:check_txnvar("txn_id",$params['transactionResponse']->transaction_id)==false))
				{
					//Finish up order here
					
					$order['time']		= time();
					$order['valid']		= 1;
					$order['name']		= $params['vars']['billing_name'];
					$order['address']	= $params['vars']['billing_line1']."\n".$params['vars']['billing_line2']."\n".$params['vars']['billing_line3']."\n".$params['vars']['billing_line4'];
					$order['postcode']	= $params['vars']['billing_postcode'];
					$order['country']	= $params['vars']['billing_country'];
					$order['tel']		= $params['vars']['billing_phone'];
					$order['email']		= $params['vars']['billing_email'];
					
					$order['delivery_name']		= $params['vars']['delivery_name'];
					$order['delivery_address']	= $params['vars']['delivery_line1']."\n".$params['vars']['delivery_line2']."\n".$params['vars']['delivery_line3']."\n".$params['vars']['delivery_line4'];
					$order['delivery_postcode']	= $params['vars']['delivery_postcode'];
					$order['delivery_country']	= $params['vars']['delivery_country'];
					$order['delivery_email']	= $params['vars']['delivery_email'];
					$order['delivery_phone']	= $params['vars']['delivery_phone'];

					$order['txnvars']['txn_id']=$params['transactionResponse']->transaction_id;
					$order['txnvars']['name']=$params['transactionResponse']->first_name." ".$params['transactionResponse']->last_name;
					$order['txnvars']['address']=$params['transactionResponse']->address."\n".$params['transactionResponse']->city."\n".$params['transactionResponse']->state;
					$order['txnvars']['postcode']=$params['transactionResponse']->zip_code;
					$order['txnvars']['country']=$params['transactionResponse']->country;
					$order['txnvars']['tel']=$params['transactionResponse']->phone;
					$order['txnvars']['email']=$params['transactionResponse']->email_address;
					$order['txnvars']['auth_code']=$params['transactionResponse']->authorization_code;
					$order['txnvars']['account_number']=$params['transactionResponse']->account_number;
					
					if($params['txnvars']['customerProfileId'])
					$order['txnvars']['customerProfileId'] = $params['txnvars']['customerProfileId'];
					if($params['txnvars']['customerAddressId'])
					$order['txnvars']['customerAddressId'] = $params['txnvars']['customerAddressId'];
					if($params['txnvars']['paymentProfileId'])
					$order['txnvars']['paymentProfileId'] = $params['txnvars']['paymentProfileId'];

					$order['paid']=$params['transactionResponse']->amount;
					$order['status']="finished";
					$order['redirect']=true;
				}
				else
				{
					$order['status']="invalid";
					$order['redirect']=false;
				}
				
			return $order;
			
		}
		/*
		 * Functions to mantain the customer profile on Authorize.net server
		 */
		
		
		/**
	     * Create a transaction.
	     * @param array $params
		 */
		function createCustomerProfileTransaction(&$params) {
			if ($handle = fopen($this->config['path'].'debug.txt', 'a')) 
			{
				fwrite($handle, "\n".'===== '.date('d/m/Y H:i:s').' createCustomerProfileTransaction ====='."\n".var_export($params, true));
				fclose($handle);
			}
			
			$params['vars']['shippingcharge']=number_format($params["vars"]["shipping"]+$params['vars']['packing'],2,".","");
			$params['vars']['transactiontax']=0;
			$params['vars']['transactionamount']=number_format($params["vars"]["total"]+$params["vars"]["shipping"]+$params['vars']['packing']+$params['vars']['tax'],2,".","");
			
			$request = new AuthorizeNetCIM($this->config['psp']['api_login_id'],$this->config['psp']['transaction_key']);
			
			$transaction = new AuthorizeNetTransaction;
			$transaction->amount = $params['vars']['transactionamount'];
			$transaction->customerProfileId = $params['customerProfileId'];
			$transaction->customerPaymentProfileId = $params['paymentProfileId'];
    		$transaction->customerShippingAddressId = $params['customerAddressId'];
    		$transaction->order->purchaseOrderNumber =  $params['session_id'];
    		$transaction->order->purchaseOrderNumber =  $params['session_id'];
    		$transaction->cardCode =  $params['cvv'];
    		
    		// Do the transaction
    		$response = $request->createCustomerProfileTransaction($this->config['psp']['type2'], $transaction);
			
			if ($handle = fopen($this->config['path'].'debug.txt', 'a')) 
			{
				fwrite($handle, "\n".'transaction:'."\n".var_export($transaction, true));
				fwrite($handle, "\n".'response:'."\n".var_export($response, true));
				fwrite($handle, "\n".'transactionResponse:'."\n".var_export($response->getTransactionResponse(), true));
				fclose($handle);
			}
			
		    $params['transactionResponse'] = $response->getTransactionResponse();
			
		    return $response->isOk();
		    
			
			
		}
		
		/**
	     * Capture a transaction.
	     * @param array $params
		 */
		function captureTransaction(&$params, &$error_reason = null) {
			$params['vars']['transactionamount']=number_format($params["vars"]["total"]+$params["vars"]["shipping"]+$params['vars']['packing']+$params['vars']['tax'],2,".","");
			
			$request = new AuthorizeNetAIM($this->config['psp']['api_login_id'],$this->config['psp']['transaction_key']);
			$response = $request->priorAuthCapture($params['transaction_id'], $params['vars']['transactionamount']);
			
			$ok = $response->approved === true;
			if(!$ok)
				$error_reason = array(
					'response_code' => $response->response_code
					,'response_subcode' => $response->response_subcode
					,'response_reason_code' => $response->response_reason_code
					,'response_reason_text' => $response->response_reason_text
				);
			
			return $ok;
		}
		
		/**
	     * Cancel a transaction.
	     * @param array $params
		 */
		function Cancel(&$params, &$error_reason = null) {
			$request = new AuthorizeNetAIM($this->config['psp']['api_login_id'],$this->config['psp']['transaction_key']);
			$response = $request->void($params['transaction_id']);
			
			$ok = $response->approved === true;
			if(!$ok)
				$error_reason = array(
					'response_code' => $response->response_code
					,'response_subcode' => $response->response_subcode
					,'response_reason_code' => $response->response_reason_code
					,'response_reason_text' => $response->response_reason_text
				);
			
			return $ok;
		}
		
		/**
		 * 
		 * Generate a refund for an order
		 * @param unknown_type $params
		 */
		function Refund(&$params) 
		{
			$request = new AuthorizeNetAIM($this->config['psp']['api_login_id'],$this->config['psp']['transaction_key']);
			$response = $request->credit($params['transaction_id'], $params['amount'], $params['account_number']);
			
			$ok = $response->approved === true;
			if(!$ok)
				$error_reason = array(
					'response_code' => $response->response_code
					,'response_subcode' => $response->response_subcode
					,'response_reason_code' => $response->response_reason_code
					,'response_reason_text' => $response->response_reason_text
				);
			
			return $ok;
			/*
			$transaction = new AuthorizeNetTransaction;
			
			$transaction->transId = $params['txn_id'];
			$transaction->creditCardNumberMasked = $params['account_number'];
			$transaction->amount = $params['amount'];
			
			if($params['customerProfileId']) {
				$transaction->customerProfileId = $params['customerProfileId'];
				$transaction->customerPaymentProfileId = $params['customerPaymentProfileId'];
			}
			
			
			
			
			$request = new AuthorizeNetCim($this->config['psp']['api_login_id'],$this->config['psp']['transaction_key']);

			// Do the transaction
    		$response = $request->createCustomerProfileTransaction('Refund', $transaction);
    		
    		
    		if( (string)$response->xml->messages->resultCode == 'Error') {
    			$params['error'] = (string) $response->xml->directResponse;
    		}
    		
    		//$params['error'] = 
    		return $response->isOk();*/
		}
		
		/**
		 * 
		 * Create Customer Profile on Authorize.net
		 * @param internal $user_id
		 * @param string $email
		 */
		function CreateCustomerProfile ( $user_id, $email ) {
			
			$transaction = new AuthorizeNetCIM($this->config['psp']['api_login_id'],$this->config['psp']['transaction_key']);
			$response = $transaction->createCustomerProfile(array(
										'merchantCustomerId' 	=> $user_id,
										'email'					=> $email
										));
			if($response->isOk())
				return  $response->xml->customerProfileId;
			else
				return false;
			
		}
		
		
		
		/**
		 * 
		 * Delete Customer Profile from Authorize.net
		 * @param unknown_type $customer_profile_id
		 * 
		 */
		function DeleteCustomerProfile ( $customer_profile_id ) {
			$transaction = new AuthorizeNetCIM($this->config['psp']['api_login_id'],$this->config['psp']['transaction_key']);
			
			$response = $transaction->deleteCustomerProfile($profile_id);
			
			return $response->isOk();
		}
		
		/**
		 * 
		 * Load a customer profile
		 * @param int $customer_profile_id
		 */
		function getCustomerProfile ( $customer_profile_id ) {
			$transaction = new AuthorizeNetCIM($this->config['psp']['api_login_id'],$this->config['psp']['transaction_key']);
			$response = $transaction->getCustomerProfile ( $customer_profile_id );
			
			if($response->isOk()) 
				return $response->xml; 
			else 
				return false;
			
		}
		
		
		/**
		 * Generate a transaction token
		 * @param int $customer_profile_id
		 */
		function getHostedProfilePage( $customer_profile_id ) {
			$transaction = new WsAuthorizeNetCIM($this->config['psp']['api_login_id'],$this->config['psp']['transaction_key']);
			
			$response = $transaction->getHostedProfilePage( $customer_profile_id );
			
			if($response->isOk()) 
				return $response->xml->token; 
			else 
				return false;
			
		}
	
		/**
		 * Get profile details
		 * @param int $customer_profile_id
		 */		
		function getCustomerProfileRequest ( $customer_profile_id ) {
			$transaction = new AuthorizeNetCIM($this->config['psp']['api_login_id'],$this->config['psp']['transaction_key']);
			
			$response = $transaction->getCustomerProfile($customer_profile_id);
			
			if($response->isOk())  
				return $response;
			else 
				return false;
		}
	
		/**
		 * Delete a customer payment profile
		 * @param int $customer_profile_id
		 * @param int $payment_profile_id
		 */
		function deleteCustomerPaymentProfile ( $customer_profile_id , $payment_profile_id ) {
			
			$transaction = new AuthorizeNetCIM($this->config['psp']['api_login_id'],$this->config['psp']['transaction_key']);
			
			$response = $transaction->deleteCustomerPaymentProfile ( $customer_profile_id, $payment_profile_id );
			
			if($response->isOk()) 
				return true; 
			else 
				return $response->xml;
		}
		
		
		/**
		 * Delete a customer shipping address
		 * @param int $customer_profile_id
		 * @param int $shipping_profile_id
		 */
		function deleteCustomerShippingAddress ( $customer_profile_id , $shipping_profile_id ) {
			
			$transaction = new AuthorizeNetCIM($this->config['psp']['api_login_id'],$this->config['psp']['transaction_key']);
			
			$response = $transaction->deleteCustomerShippingAddress ( $customer_profile_id, $shipping_profile_id );
			
			if($response->isOk()) 
				return true; 
			else 
				return $response->xml;
		}
		
		
	}