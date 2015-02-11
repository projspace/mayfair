<?
	/**
	 * e-Commerce System Data Feed/Export Plugin
	 * Copyright (c) 2002-2006 Philip John, All Rights Reserved.
	 * Author	: Philip John
	 * Version	: 6.0
	 *
	 * PROPRIETARY/CONFIDENTIAL.  Use is subject to license terms.
	 */
?>
<?
	include("../lib/cfg_Options.php");
	include("../lib/cfg_Config.php");
	include("../lib/adodb/adodb.inc.php");
	include("../lib/act_OpenDB.php");
	include("../lib/lib_Session.php");
	include("../lib/lib_Smarty.php");
	//Elements------------
	include("../lib/lib_Elements.php");
	include("../lib/lib_CustomElements.php");
	$elems=new CustomElements($db,$smarty,$config,$session->session_id);
	//--------------------
	include("../lib/lib_UserSession.php");
	include("../lib/lib_Common.php");
	include("rates.php");
	
	$results=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				shop_variables
			WHERE
				name IN ('vat','from','gift_days_advance','gift_name_min_length','gift_phone_min_digits','gift_pagination')
		"
		)
	);
	
	
	while($row = $results->FetchRow())
	{
		if($row['name'] == 'vat')
			define('VAT', $row['value']);
		if($row['name'] == 'from')
			define('FROM', $row['value']);
		if($row['name'] == 'gift_days_advance')
			define('GIFT_DAYS_ADVANCE', $row['value']);
		if($row['name'] == 'gift_name_min_length')
			define('GIFT_NAME_MIN_LENGTH', $row['value']);
		if($row['name'] == 'gift_phone_min_digits')
			define('GIFT_PHONE_MIN_DIGITS', $row['value']);
		if($row['name'] == 'gift_pagination')
			define('GIFT_PAGINATION', $row['value']);
	}

	if(!isset($category_id))
		$category_id=1;

	if(TESTCOOKIE)
	{
		if($Fusebox['fuseaction']!="callback")
		{
			$url=$_SERVER['REQUEST_URI'];
			if(strpos($url,"?")!==false)
				$url=$url."&";
			else
				$url=$url."?";
			$url=$url.$config['shop']['session_id']."=".$session->session_id;
			header("location: $url");
		}
	}
	
	if($user_session->check())
		$user_session->update();
	
	$trail = array('menu'=>array(), 'submenu'=>array());
	$trail['menu'][] = array('url'=>$config['dir'], 'name'=>'Shop');
	
	
	switch($Fusebox['fuseaction'])
	{
		case "main":
		case "Fusebox.defaultFuseaction":
			$category_id=1;
			include("qry_Category.php");
			include("dsp_Category.php");
			break;
			
		case "sign_up":
			include("act_SignUp.php");
			break;

		case "currency":
			header("P3P: ".$config['p3p']);
			setcookie("currency",safe($_POST['currency']),0,$config['dir']);
			header("location: ".$return);
			break;

		case "products":
			include("qry_Products.php");
			include("dsp_Products.php");
			break;

		case "search":
			include("../lib/lib_Search.php");
			include("qry_Search.php");
			include("dsp_Search.php");
			break;
			
		case "postcode_search":

			$keyword = safe($_GET['keyword']);
			
			/*if($keyword != ""){
				include("qry_VerifySearch.php");
				if($flag === false){
					header("location: ".$config['dir'].'zip-search');
				}
			}*/
			include("qry_PostcodeSearch.php");
			include("dsp_PostcodeSearch.php");
			break;

		case "categorySearch":
			include("qry_CategorySearch.php");
			include("qry_Category.php");
			include("dsp_CategorySearch.php");
			break;

		case "priceSearch":
			include("qry_PriceSearch.php");
			include("dsp_PriceSearch.php");
			break;

		case "advancedSearch":
			include("qry_AdvancedSearch.php");
			include("dsp_AdvancedSearch.php");
			break;

		case "category":
			if(!isset($category_id))
				$category_id=1;
			include("qry_Category.php");
			if(count($subcategories))
				include("dsp_TopCategory.php");
			else
			{
				/*if(MOBILE_DEV)
				{
					include("qry_CategoryMobile.php");
					include("dsp_CategoryMobile.php");
				}
				else*/
					include("dsp_Category.php");
			}
			break;

		case "view_by_category":
            $elems->meta['title'] = 'Shop by Category / Mayfair House';
			include("qry_ViewByCategory.php");
			include("dsp_ViewByCategory.php");
			break;

        case "shop_by_brand":
            $elems->meta['title'] = 'Shop by Brand / Mayfair House';
            if(!($_REQUEST['brand_id']+0))
            {
                include("qry_ShopByBrand.php");
                include("dsp_ShopByBrand.php");
            }
            else
			{
				include("qry_Brand.php");
				if($brand && $brand['content_visible']+0)
				{
					$category = array(
						'content_visible' => 1,
						'content_image' => trim($brand['content_imagetype'])?$config['dir'].'images/brand/content/'.$brand['id'].'.'.$brand['content_imagetype']:'',
						'name' => $brand['name'],
						'content' => $brand['content']
					);
				}
                include("dsp_Category.php");
			}
			break;

        case "product":
			include("qry_Product.php");
			include("dsp_Product.php");
			break;
			
		case "quick_product":
			include("qry_Product.php");
			include("dsp_ProductQuick.php");
			break;
			
		case "360_view":
			include("qry_360View.php");
			include("dsp_360View.php");
			break;
			
		case "video":
			include("qry_Product.php");
			include("dsp_ProductVideo.php");
			break;
			
		case "addReview":	
			include("../lib/lib_Validation.php");
			include("val_Review.php");
				
			if(safe($_REQUEST['act'])=="")
			{
				include("qry_AddReview.php");
				include("dsp_AddReview.php");
			}
			else
			{
				if($validator->validate($_POST))
				{
					include("act_AddReview.php");
					include("qry_AddReview.php");
					include("dsp_ReviewStatus.php");
				}
				else
				{
					include("qry_AddReview.php");
					include("dsp_AddReview.php");
				}
			}
			break;

		case "image":
			include("qry_Image.php");
			include("dsp_Image.php");
			break;

		case "cart":
			if(safe($_REQUEST['act'])=="")
			{
				$page="cart";
				include("qry_CartSettings.php");
				include("qry_CartContents.php");
				include("dsp_CartContents.php");
				$elems->meta['title']="Basket / ".$config['meta']['title'];
				$trail['menu'][] = array('url'=>$config['dir'].'cart', 'name'=>'Basket');
			}
			else if(safe($_REQUEST['act'])=="saveDetails")
			{
				include("qry_CartSettings.php");
				include("act_UpdateCartDetails.php");
				if(safe($_REQUEST['return_url']) == '')
				{
					$url=$config['dir']."checkout";
					if(!USECOOKIE && !SEARCHENGINE)
						$url.="?".$config['shop']['session_id']."=".$session->session_id;
				}
				else
					$url=safe($_REQUEST['return_url']);
				header("location: $url");
			}
			else if(safe($_REQUEST['act'])=="saveDelivery")
			{
				include("act_UpdateDeliveryCountry.php");
				header("location: ".$config['dir'].'cart');
			}
			break;
			
		case "wishlist":
			switch(safe($_REQUEST['act']))
			{
				case 'add':
					include("act_WishlistAdd.php");
					header("location: ".$config['dir'].'cart');exit;
					break;
				case 'insert':
					include("act_WishlistInsert.php");
                    if($_REQUEST['ajax'])
                        echo '<script language="javascript" type="text/javascript">/* <![CDATA[ */ parent.window.location = "'.$config['dir'].'cart"; /* ]]> */ </script>';
                    else
                        header("location: ".$config['dir'].'cart');
                    exit;
					break;
				case 'remove':
					include("act_WishlistRemove.php");
					if(safe($_REQUEST['return_url']) == '')
						$url=$config['dir']."cart";
					else
						$url=safe($_REQUEST['return_url']);
					header("location: $url");exit;
					break;
				case 'move':
                    if($_REQUEST['confirmation'])
                    {
                        if($_POST['is_post'])
                        {
                            include("act_Wishlist2CartConfirmation.php");
                            header("Location: ".$config['dir'].'wishlist/cart/'.$_REQUEST['wish_id']);
                            exit;
                        }
                        include("dsp_Wishlist2Cart.php");
                    }
                    else
                    {
                        if($session->session->fields['last_gift_list_id']+0)
                        {
                            header("Location: ".$config['dir'].'wishlist/cart/'.$_REQUEST['wish_id'].'?confirmation=1');
                            exit;
                        }

                        include("act_Wishlist2Cart.php");
                        header("location: ".$config['dir'].'cart');exit;
                    }
                    break;
			}
			break;

		case "add":
			include("act_Add.php");
			//$url=$config['dir']."cart";
			if(trim($_REQUEST['return_url']) != '')
				$url = trim($_REQUEST['return_url']);
			else
				$url=$_SERVER['HTTP_REFERER'];
			$_SESSION['show_cart'] = true;
			if(!USECOOKIE && !SEARCHENGINE)
				$url.="?".$config['shop']['session_id']."=".$session->session_id;
			header("location: $url");
			break;

		case "increase":
			include("act_Increase.php");
			$url=$config['dir']."cart";
			if(!USECOOKIE && !SEARCHENGINE)
				$url.="?".$config['shop']['session_id']."=".$session->session_id;
			header("location: $url");
			break;

		case "decrease":
			include("act_Decrease.php");
			$url=$config['dir']."cart";
			if(!USECOOKIE && !SEARCHENGINE)
				$url.="?".$config['shop']['session_id']."=".$session->session_id;
			header("location: $url");
			break;

		case "update":
			include("act_Update.php");
			$url=$config['dir']."cart";
			if(!USECOOKIE && !SEARCHENGINE)
				$url.="?".$config['shop']['session_id']."=".$session->session_id;
			header("location: $url");
			break;

		case "remove":
			include("act_Remove.php");
			$url=$config['dir']."cart";
			if(!USECOOKIE && !SEARCHENGINE)
				$url.="?".$config['shop']['session_id']."=".$session->session_id;
			header("location: $url");
			break;

		case "clear":
			include("act_Clear.php");
			$url=$config['dir']."cart";
			if(!USECOOKIE && !SEARCHENGINE)
				$url.="?".$config['shop']['session_id']."=".$session->session_id;
			header("location: $url");
			break;

		case "delivery":
			$elems->meta['title']="Delivery / ".$config['meta']['title'];
			$trail['menu'][] = array('url'=>$config['dir'].'cart', 'name'=>'Basket');
			$trail['menu'][] = array('url'=>$config['dir'].'delivery', 'name'=>'Delivery');
			$act = safe($_REQUEST['act']);
			if($act=="")
			{
				include("qry_Delivery.php");
				if($user_session->check())
					include("dsp_Delivery.php");
				else
					include("dsp_DeliveryLogin.php");
			}
			else
			if($act=="chooseAddress")
			{
				include("act_ChooseAddress.php");
				header("location: ".$config['dir']."billing");exit;
			}
			else
			if($act=="saveAddress")
			{
				include("act_SaveAddress.php");
			}
			else
			if($act=="useAddress")
			{
				include("act_UseAddress.php");
				header("location: ".$config['dir']."billing");exit;
			}
			break;
			
		case "billing":
			$elems->meta['title']="Billing / ".$config['meta']['title'];
			$trail['menu'][] = array('url'=>$config['dir'].'cart', 'name'=>'Basket');
			$trail['menu'][] = array('url'=>$config['dir'].'delivery', 'name'=>'Delivery');
			$trail['menu'][] = array('url'=>$config['dir'].'billing', 'name'=>'Billing');
			$act = safe($_REQUEST['act']);
			if($act=="")
			{
				include("qry_Billing.php");
				if($user_session->check())
					include("dsp_BillingAccount.php");
				else
					include("dsp_BillingNoAccount.php");
			}
			else
			if($act=="chooseAddress")
			{
				include("act_ChooseBilling.php");
				header("location: ".$config['dir']."checkout?act=payment");exit;
			}
			else
			if($act=="saveAddress")
			{
				include("act_SaveBilling.php");
			}
			else
			if($act=="useAddress")
			{
				include("act_UseBilling.php");
				header("location: ".$config['dir']."checkout?act=payment");exit;
			}
			break;

		case "checkout":
			
			
			include("../lib/lib_Payment.php");
			include("../lib/payment/lib_".$config['psp']['driver'].".php");
			include("../lib/payment/cfg_".$config['psp']['driver'].".php");
			$smarty->assign("config",$config);
			$psp =& new $config['psp']['driver']($config,$smarty,$db);
			$psp->setSession($session);

			include("../lib/lib_Executor.php");
			
			$act = safe($_REQUEST['act']);

            $page = $elems->qry_Page(30);
            $elems->meta['title'] = $page['meta_title'];
            $elems->meta['keywords'] = $page['meta_keywords'];
            $elems->meta['description'] = $page['meta_description'];

			if($act=="")
			{
				include("../lib/lib_Validation.php");
				
				if($user_session->check()) {
					include("val_AuthorizeAddresses.php");					
					include ("../users/qry_AccountOnly.php");
					
					// 	Query the address from the Authorize.net Payment
                    if($config['psp']['driver'] == 'Authorize')
					    $authorize_profile_xml = $psp->getCustomerProfileRequest ( $account['authorize_profile_id'] );
					
					include("qry_Countries.php");
					include("dsp_AuthorizeAddresses.php");
					
					
					
				} else {
					include("../users/val_Login.php");
					$login_validator = $validator;
					include("val_Addresses.php");
					
					include("qry_AdditionalPayment.php");
					include("qry_Countries.php");
					include("qry_Addresses.php");
					include("dsp_Addresses.php");
					
				}
			}
			else if($act=="login")
			{
				include("../lib/lib_Validation.php");
				include("../users/val_Login.php");
				
				if($validator->validate($_POST))
				{
					include("../users/act_Login.php");
					if(!$user)
					{
						$login_validator = $validator;
						include("val_Addresses.php");
						
						include("qry_Countries.php");
						include("qry_Addresses.php");
						include("dsp_Addresses.php");
					}
				}
				else
				{
					$login_validator = $validator;
					include("val_Addresses.php");
					
					include("qry_Countries.php");
					include("qry_Addresses.php");
					include("dsp_Addresses.php");
				}
			}
			else if($act=="saveLogedInDetails") {
				
				if($fl = fopen(dirname(__FILE__)."/../debug.txt","a")) {
					fwrite($fl, PHP_EOL." Date ".date("Y-m-d H:i:s"));
					fwrite($fl, "Action: saveLogedInDetails\n $_POST: ".print_r($_POST,true));
					fclose($fl);
				}
				
				
				include("../lib/lib_Validation.php");
				include("val_AuthorizeAddresses.php");
				
				if($validator->validate($_POST)) {
					
					include("act_UpdateAddresses.php");
					header("location: ".$config['dir']."checkout?act=taxes");exit;
					
				} else {
					include ("../users/qry_AccountOnly.php");
					
					// 	Query the address from the Authorize.net Payment
					$authorize_profile_xml = $psp->getCustomerProfileRequest( $account['authorize_profile_id'] );
					
					include("qry_Countries.php");
					include("dsp_AuthorizeAddresses.php");
				}
			}
			else if($act=="saveDetails")
			{
				include("../lib/lib_Validation.php");
				include("../users/val_Login.php");
				$login_validator = $validator;
				include("val_Addresses.php");
				
				if($validator->validate($_POST))
				{
					include("act_UpdateAddresses.php");
					header("location: ".$config['dir']."checkout?act=taxes");exit;
				}
				else
				{
					include("qry_Countries.php");
					include("qry_Addresses.php");
					include("dsp_Addresses.php");
				}
			}
			else if($act=="saveShippingService")
			{
				include("act_UpdateShippingService.php");
				header("location: ".$config['dir']."checkout?act=taxes");exit;
			}
			elseif($act == 'taxes')
			{
				include("../lib/lib_WSTax.php");
				include("../lib/lib_Shipping.php");
				if($session->session->fields['delivery_speedtax_status'] != 'FULL') { header("location: ".$config['dir']."checkout"); exit; }
				
				include("qry_CartSettings.php");
				include("qry_CartContentsCheckout.php");
				include("dsp_CartContentsCheckout.php");
				//$elems->meta['title']="Basket / ".$config['meta']['title'];
				$trail['menu'][] = array('url'=>$config['dir'].'cart', 'name'=>'Basket');
			}
			elseif($act == 'payment')
			{
				include("../lib/lib_WSTax.php");
				include("../lib/lib_Shipping.php");
				include("act_ValidateCart.php");
				if(!$ok){ header("location: ".$config['dir']."cart"); exit; }
				include("qry_CartContentsCheckout.php");
				if($vars['tax'] === false || $vars['shipping'] === false){ header("location: ".$config['dir']."cart"); exit; }
				
				$executor=new Executor($vars,$config['path']);
				$vars=$executor->calc();
				include("act_SaveValues.php");
				include("qry_CheckoutParams.php");
				if($session->session->fields['additional_payment'])
				{
					include("qry_AdditionalPayment.php");
					$ok = $psp->AdditionalPayment($params, $additional_payment);
					include("act_ResetSession.php");
					if($ok)
						header("location: ".$config['dir']."order-confirmation/".$params['session_id']);
					else
						header("location: ".$config['dir']."order-failed");
					exit;
				}
				else
				{
					
					$psp->Details($params);
					include("act_ResetSession.php");
				}
			}
			elseif($act == 'paymentAccount')
			{
				if($fl = fopen(dirname(__FILE__)."/../debug.txt","a")) {
					fwrite($fl, PHP_EOL." Date ".date("Y-m-d H:i:s"));
					fwrite($fl, "Action: paymentAccount\n $_REQUEST: ".print_r($_REQUEST,true));
					fclose($fl);
				}
				
				include("../lib/lib_WSTax.php");
				include("../lib/lib_Shipping.php");
				if(!$user_session->check()){ header("location: ".$config['dir']."cart"); exit; }
				include("act_ValidateCart.php");
				if(!$ok){ header("location: ".$config['dir']."cart"); exit; }
				include("qry_CartContentsCheckoutAuthorize.php");
				if($vars['tax'] === false || $vars['shipping'] === false){ header("location: ".$config['dir']."cart"); exit; }
				
				$executor=new Executor($vars,$config['path']);
				$vars=$executor->calc();
				include("act_SaveValues.php");
				include("qry_CheckoutParams.php");
				
				$params['customerProfileId'] 	= $session->session->fields['customerProfileId'];
				$params['customerAddressId'] 	= $session->session->fields['customerAddressId'];
				$params['paymentProfileId']		= $session->session->fields['paymentProfileId'];
				$params['cvv']		            = $session->session->fields['cvv'];

					
				$details['txnvars']['customerProfileId'] 	=	$params['customerProfileId'];
				$details['txnvars']['customerAddressId'] 	=	$params['customerAddressId'];
				$details['txnvars']['paymentProfileId']		= 	$params['paymentProfileId'];
				
				include ("act_SaveDetails.php");
				
				if($psp->createCustomerProfileTransaction($params)) {
					// the order went OK
					$session_id = $session->session_id;
					unset($session);
					$session =& new session($db,$config,$session_id);
					$psp->SetSession($session);
					
					include("qry_CartContentsCallback.php");
					include("qry_CheckoutParamsCallback.php");

					$order=$psp->CallbackCIM($params);
					
					if ($handle = fopen($config['path'].'debug.txt', 'a'))
					{
						fwrite($handle, "\n".'===== '.date('d/m/Y H:i:s').' Callback 2 ====='."\n".var_export($order, true));
						fclose($handle);
					}
					
					if($order['status']=="finished")
					{
						include("act_FinishOrder.php");
						
						//Send email confirmation
						include("../lib/lib_Email.php");
						include("act_SendOrderConfirmation.php");

						include("act_ClearCallback.php");
						
						include("qry_FCOrder.php");
						include("act_FCOrder.php");
						
						include("act_ResetSession.php");
						header("location: ".$config['dir']."order-confirmation/".$params['session_id']);
					}
					else
					{
						include("act_ResetSession.php");
						header("location: ".$config['dir']."order-failed");
					}
					exit;
				}
				else {
					include("act_ResetSession.php");
					if($fl = fopen(dirname(__FILE__)."/../debug.txt","a")) {
						fwrite($fl, PHP_EOL." Date ".date("Y-m-d H:i"));
						fwrite($fl, "Action: paymentAccount\n $_POST: ".print_r($params,true));
						fclose($fl);
					}
					header("location: ".$config['dir']."order-failed");
					die('error');
					
				}
				exit;
			}
			
			break;

		case "callback":
			if ($handle = fopen($config['path'].'debug.txt', 'a')) 
			{
				fwrite($handle, "\n".'===== '.date('d/m/Y H:i:s').' ====='."\n".var_export($_REQUEST, true)."\n".var_export($_SERVER, true));
				fclose($handle);
			}
			
			$db->setMagicQuotes(false);
			//PSP Driver
			include("../lib/lib_Payment.php");
			include("../lib/payment/lib_".$config['psp']['driver'].".php");
			include("../lib/payment/cfg_".$config['psp']['driver'].".php");
			$smarty->assign("config",$config);
			$psp =& new $config['psp']['driver']($config,$smarty,$db);

			//Check to see if the session object is valid, otherwise get the session_id and create a valid one
			if($psp->SessionID($_REQUEST)!==false)
			{
				unset($session);
				$session =& new session($db,$config,$psp->SessionID($_REQUEST));
			}
			else
				return;
			$psp->SetSession($session);

			include("qry_CartContentsCallback.php");
			include("qry_CheckoutParamsCallback.php");

			$order=$psp->Callback($params);
			
			if ($handle = fopen($config['path'].'debug.txt', 'a'))
			{
				fwrite($handle, "\n".'===== '.date('d/m/Y H:i:s').' Callback 1 ====='."\n".var_export($order, true));
				fclose($handle);
			}
			
			if($order['status']=="finished")
			{
				include("act_FinishOrder.php");
				
				if($order['redirect'] && $order_id)
				{
					$order['status'].="?order_id=".$order_id;
					foreach($_REQUEST as $key=>$value)
						if(strpos($key, '__utm') === 0)
							$order['status'] .= '&'.$key.'='.$value;
				}
				
				//Send email confirmation
				include("../lib/lib_Email.php");
				include("act_SendOrderConfirmation.php");

				include("act_ClearCallback.php");
				
				include("qry_FCOrder.php");
				include("act_FCOrder.php");
				
			} else {
				$order['status'] = 'invalid';
				$order['redirect'] = true;
			}
			
			if($order['redirect']) {
				
				include("url_Status.php");
			}
		break;
		
		case "finished":
			if ($handle = @fopen($config['path'].'debug_ga.txt', 'a')) 
			{
				fwrite($handle, "\n".'===== '.date('d/m/Y H:i:s').' FINISHED 1 ====='."\n_REQUEST: ".var_export($_REQUEST, true)."\n_SERVER: ".var_export($_SERVER, true)."\n".ob_get_contents());
				fclose($handle);
			}
			$config['protocol'] = 'https://';
			$config['dir'] = str_replace('http://', 'https://', $config['dir']);
			$config['layout_dir'] = str_replace('http://', 'https://', $config['layout_dir']);

            $page = $elems->qry_Page(30);
            $elems->meta['title'] = $page['meta_title'];
            $elems->meta['keywords'] = $page['meta_keywords'];
            $elems->meta['description'] = $page['meta_description'];

			include("../lib/lib_Validation.php");
			include("../users/val_Register.php");
			//include("qry_AdditionalPayment.php");
			//$user_additional_payment = $additional_payment;
			include("qry_Finished.php");
			
			include("dsp_Finished.php");
			if ($handle = @fopen($config['path'].'debug_ga.txt', 'a')) 
			{
				fwrite($handle, "\n".'===== '.date('d/m/Y H:i:s').' FINISHED 2 ====='."\n".ob_get_contents());
				fclose($handle);
			}
			break;

		case "declined":
			include("../lib/lib_Payment.php");
			include("../lib/payment/lib_".$config['psp']['driver'].".php");
			include("../lib/payment/cfg_".$config['psp']['driver'].".php");
			$smarty->assign("config",$config);
			$psp=new $config['psp']['driver']($config,$smarty,$db);
			$psp->Declined($_REQUEST);
			break;

		case "cancelled":
			include("../lib/lib_Payment.php");
			include("../lib/payment/lib_".$config['psp']['driver'].".php");
			include("../lib/payment/cfg_".$config['psp']['driver'].".php");
			$smarty->assign("config",$config);
			$psp=new $config['psp']['driver']($config,$smarty,$db);
			
			include("dsp_Cancelled.php");
			break;

		case "invalid":
			$config['protocol'] = 'https://';
			$config['dir'] = str_replace('http://', 'https://', $config['dir']);
			$config['layout_dir'] = str_replace('http://', 'https://', $config['layout_dir']);
			include("../lib/lib_Payment.php");
			include("../lib/payment/lib_".$config['psp']['driver'].".php");
			include("../lib/payment/cfg_".$config['psp']['driver'].".php");
			$smarty->assign("config",$config);
			$psp=new $config['psp']['driver']($config,$smarty,$db,$session);
			
			include("dsp_Invalid.php");
			break;
			
		case "google_code":
			include("qry_Finished.php");
			include("dsp_GoogleCode.php");
			if ($handle = @fopen($config['path'].'debug_ga.txt', 'a')) 
			{
				fwrite($handle, "\n".'===== '.date('d/m/Y H:i:s').' GOOGLE CODE ====='."\n_REQUEST: ".var_export($_REQUEST, true)."\n_SERVER: ".var_export($_SERVER, true)."\n".ob_get_contents());
				fclose($handle);
			}
			exit;
			break;
			
		case "fc_order":
			$order_id = 1074;
			include("qry_FCOrder.php");
			include("act_FCOrder.php");
			break;

		default:
			break;
	}
?>
