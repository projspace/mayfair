<?
	include("../lib/adodb/adodb.inc.php");
	include("../lib/act_OpenDB.php");
	include("../lib/lib_Session.php");
	include("../lib/lib_Smarty.php");
	//Elements
	include("../lib/lib_Elements.php");
	include("../lib/lib_CustomElements.php");
	$elems=new CustomElements($db,$smarty,$config,$session->session_id);
	
	include("../lib/lib_UserSession.php");
	include("../lib/lib_Common.php");
	
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
	
	if($user_session->check())
		$user_session->update();
		
	$trail = array('menu'=>array(), 'submenu'=>array());
	$trail['menu'][] = array('url'=>$config['dir'], 'name'=>'Shop');

	switch($Fusebox["fuseaction"])
	{
		case "main":
		case "Fusebox.defaultFuseaction":
			if($user_session->check())
			{	
				$act = safe($_REQUEST['act']);
				if($act=="")
				{
					include("../shop/qry_AdditionalPayment.php");
					include("qry_Account.php");
					
					if($config['psp']['driver'] == 'Authorize') {
						
							include ("../lib/lib_Payment.php");
							include ("../lib/payment/cfg_Authorize.php");
							include ("../lib/payment/lib_Authorize.php");
									
							$psp = new Authorize($config,$smarty,$db);
							
							$authorize_profile_xml = $psp->getCustomerProfileRequest( $account['authorize_profile_id'] );
					}
					
					include("dsp_Account.php");
				}
			}
			else
				header("Location: ".$config["dir"].'login');
			break;
			
		case "addAddress":
			if($user_session->check())
			{
				include("../lib/lib_Validation.php");
				include("val_Address.php");
				
				if($_REQUEST['act']=="")
				{
					include("qry_Countries.php");
					include("dsp_AddAddress.php");
				}
				else
				{
					if($validator->validate($_POST))
					{
						include("act_AddAddress.php");
						include("dsp_AddAddressStatus.php");
					}
					else
					{
						include("qry_Countries.php");
						include("dsp_AddAddress.php");
					}
				}
			}
			else
			{
				if(isset($_REQUEST['ajax']))
					echo '<script language="javascript" type="text/javascript">/* <![CDATA[ */ $(document).ready(function(){ parent.$.fancybox.close(); }); /* ]]> */ </script>';
				else
					header("Location: ".$config["dir"]);
			}
			break;
		
		case "editAddress":
			if($user_session->check())
			{
				include("../lib/lib_Validation.php");
				include("val_Address.php");
				
				if($_REQUEST['act']=="")
				{
					include("qry_Countries.php");
					include("qry_Address.php");
					include("dsp_EditAddress.php");
				}
				else
				{
					if($validator->validate($_POST))
					{
						include("act_UpdateAddress.php");
						include("dsp_EditAddressStatus.php");
					}
					else
					{
						include("qry_Countries.php");
						include("qry_Address.php");
						include("dsp_EditAddress.php");
					}
				}
			}
			else
			{
				if(isset($_REQUEST['ajax']))
					echo '<script language="javascript" type="text/javascript">/* <![CDATA[ */ $(document).ready(function(){ parent.$.fancybox.close(); }); /* ]]> */ </script>';
				else
					header("Location: ".$config["dir"]);
			}
			break;
			
		case "editPassword":
			if($user_session->check())
			{
				$act = safe($_REQUEST['act']);
				if($act=="")
				{
					include("dsp_EditPassword.php");
				}
				else
				if($act=="save")
				{
					include("qry_Account.php");
					include("act_UpdatePassword.php");
				}
			}
			else
				header("Location: ".$config["dir"].'login');
			break;
			
		case "login":
			if(!$user_session->check())
			{
				include("../lib/lib_Validation.php");
				include("val_Login.php");

	            if($_POST['is_post'] && $validator->validate($_POST))
                    include("act_Login.php");

                if(isset($_REQUEST['ajax']))
                    include("dsp_LoginAjax.php");
                else
                    include("dsp_Login.php");
			}
			else
			{
				if(isset($_REQUEST['ajax']))
					echo '<script language="javascript" type="text/javascript">/* <![CDATA[ */ $(document).ready(function(){ parent.$.fancybox.close(); }); /* ]]> */ </script>';
				else
					header("Location: ".$config["dir"]);
			}
			break;
			
		case "logout":
			if($user_session->check())
				include("act_Logout.php");
			else
				header("Location: ".$config["dir"]);
			break;
			
		case "orders":
			if($user_session->check())
			{
				$trail['submenu'][] = array('url'=>$config['dir'].'account', 'name'=>'Your details');
				$trail['submenu'][] = array('url'=>$config['dir'].'account/orders', 'name'=>'Your orders');
				
				include("qry_Orders.php");
				include("dsp_Orders.php");
			}
			else
				header("Location: ".$config["dir"].'login');
			break;
			
		case "order":
			if($user_session->check())
			{
				include("qry_Order.php");
				include("dsp_Order.php");
			}
			else
				header("Location: ".$config["dir"].'login');
			break;
			
		case "register":
			if(!$user_session->check())
			{
				include("../lib/lib_Validation.php");
				include("val_Register.php");
				
                if($_POST['is_post'] && $validator->validate($_POST))
                {

                    include("../lib/lib_Email.php");
                    include("qry_RegisterAddress.php");
                    include("act_Register.php");
                    
                    // Create Authorize Profile Id
                    if ( $user_id && $config['psp']['driver'] == 'Authorize' ) {
                        include ("../lib/lib_Payment.php");
                        include ("../lib/payment/cfg_Authorize.php");
                        include ("../lib/payment/lib_Authorize.php");


                        $psp = new Authorize($config,$smarty,$db);
                        if ( $authorize_profile_id = $psp->CreateCustomerProfile($user_id,$_REQUEST['email']))
                            include ("act_UpdateAuthorizeProfileId.php");

                    }

                    if($user_id)
                        $user_session->start($user_id);

                    if($ok && $_REQUEST['redirect_url'] ) {
                        header('location: '.$_REQUEST['redirect_url'].((strpos($_REQUEST['redirect_url'], '?') === false)?'?':'&')."register=done");
                        die();
                    }

                    include("dsp_RegisterStatus.php");
                }
                else
                {
                    if(isset($_REQUEST['ajax']))
                        include("dsp_RegisterAjax.php");
                    else
                        include("dsp_Register.php");
				}
			}
			else
			{
				if(isset($_REQUEST['ajax']))
					echo '<script language="javascript" type="text/javascript">/* <![CDATA[ */ $(document).ready(function(){ parent.$.fancybox.close(); }); /* ]]> */ </script>';
				else
					header("Location: ".$config["dir"]);
			}
			break;
			
		case "forgottenPassword":
			if(!$user_session->check())
			{
				include("../lib/lib_Validation.php");
				include("val_ForgottenPassword.php");
				
                if($_POST['is_post'] && $validator->validate($_POST))
                {
                    include("../lib/lib_Email.php");
                    include("act_ForgottenPassword.php");
                    include("dsp_ForgottenPasswordStatus.php");
                }
                else
                {
                    if(isset($_REQUEST['ajax']))
                        include("dsp_ForgottenPasswordAjax.php");
                    else
                        include("dsp_ForgottenPassword.php");
                }
			}
			else
			{
				if(isset($_REQUEST['ajax']))
					echo '<script language="javascript" type="text/javascript">/* <![CDATA[ */ $(document).ready(function(){ parent.$.fancybox.close(); }); /* ]]> */ </script>';
				else
					header("Location: ".$config["dir"]);
			}
			break;

		case "paymentUpdate":
		case "paymentRemove":
			if($user_session->check())
			{
				include("act_UpdatePayment.php");
				header("Location: ".$config["dir"].'account');
				exit;
			}
			else
				header("Location: ".$config["dir"].'login');
			break;

		/*
		 * Add Payment Profile - a new card
		 */
		case "addAuthorizePaymentProfile":
			
			if($user_session->check())
			{
				include ("qry_UserAuthorizeProfileId.php");
				include ("../lib/lib_Payment.php");
				include ("../lib/payment/cfg_Authorize.php");
				include ("../lib/payment/lib_Authorize.php");
									
				$psp = new Authorize($config,$smarty,$db);
							
				$token = $psp->getHostedProfilePage ( $account['authorize_profile_id'] );
						
				$authorizeAction = 'addPayment';
				$payment_profile_id = 'new';
						
				include ("dsp_AuthorizePayment.php");
			}
			else
				header("Location: ".$config["dir"].'login');
		break;
		
		/*
		 * Add a shipping profile for delivery
		 */
		case "addAuthorizeShipingProfile":
			if($user_session->check())
			{
				include ("qry_UserAuthorizeProfileId.php");
				include ("../lib/lib_Payment.php");
				include ("../lib/payment/cfg_Authorize.php");
				include ("../lib/payment/lib_Authorize.php");
									
				$psp = new Authorize($config,$smarty,$db);
							
				$token = $psp->getHostedProfilePage ( $account['authorize_profile_id'] );
						
				$authorizeAction = 'addShipping';
				$payment_profile_id = 'new';
						
				include ("dsp_AuthorizePayment.php");
			}
			else
				header("Location: ".$config["dir"].'login');
		break;
		
		case "editAuthorizePaymentProfile":
			if($user_session->check())
			{
				include ("qry_UserAuthorizeProfileId.php");
				include ("../lib/lib_Payment.php");
				include ("../lib/payment/cfg_Authorize.php");
				include ("../lib/payment/lib_Authorize.php");
				$psp = new Authorize($config,$smarty,$db);
							
				$token = $psp->getHostedProfilePage ( $account['authorize_profile_id'] );

				$authorizeAction = 'editPayment';
				$payment_profile_id = $_REQUEST['payment_profile_id'];
				
				include ("dsp_AuthorizePayment.php");
			}
			else
				header("Location: ".$config["dir"].'login');
		break;
		
		/**
		 * Edit a shipping address
		 */
		case "editAuthorizeShippingProfile":
			if($user_session->check())
			{
				include ("qry_UserAuthorizeProfileId.php");
				include ("../lib/lib_Payment.php");
				include ("../lib/payment/cfg_Authorize.php");
				include ("../lib/payment/lib_Authorize.php");
				$psp = new Authorize($config,$smarty,$db);
							
				$token = $psp->getHostedProfilePage ( $account['authorize_profile_id'] );

				$authorizeAction = 'editShipping';
				$shipping_profile_id = $_REQUEST['shipping_profile_id'];
				
				include ("dsp_AuthorizePayment.php");
			}
			else
				header("Location: ".$config["dir"].'login');
		break;
		
		/**
		 * Delete a payment profile
		 */
		case "deleteAuthorizePaymentProfile":
			
			include ("../lib/lib_Payment.php");
			include ("../lib/payment/cfg_Authorize.php");
			include ("../lib/payment/lib_Authorize.php");
			$psp = new Authorize($config,$smarty,$db);
						
			$payment_profile_id = $_REQUEST['payment_profile_id'];
			$authorize_profile_id = $_REQUEST['authorize_profile_id'];
			$response = $psp->deleteCustomerPaymentProfile($authorize_profile_id,$payment_profile_id);
			
			if($response)
				die('{"status":"1"}');
			else 
				die('{"status":"0"}');
			
		break;
		
		case "deleteAuthorizeShippingProfile":
			include ("../lib/lib_Payment.php");
			include ("../lib/payment/cfg_Authorize.php");
			include ("../lib/payment/lib_Authorize.php");
			$psp = new Authorize($config,$smarty,$db);
						
			$shipping_profile_id = $_REQUEST['shipping_profile_id'];
			$authorize_profile_id = $_REQUEST['authorize_profile_id'];
			$response = $psp->deleteCustomerShippingAddress($authorize_profile_id,$shipping_profile_id);
			
			if($response)
				die('{"status":"1"}');
			else 
				die('{"status":"0"}');
			
		break;
		
		case "setDefaultAuthorizePaymentProfile":
			
			$payment_profile_id = $_REQUEST['payment_profile_id'];
			$authorize_profile_id = $_REQUEST['authorize_profile_id'];
			include ("act_SetDefaultPaymentId.php");
			die('{"status":"1"}');
		break;
		
		case "setDefaultAuthorizeShippingProfile":
			$shipping_profile_id = $_REQUEST['shipping_profile_id'];
			$authorize_profile_id = $_REQUEST['authorize_profile_id'];
			include ("act_SetDefaultShippingId.php");
			die('{"status":"1"}');
		break;
		/**
		 * Render the shop search section
		 */
		case "shopsSearch":
			if($_REQUEST['act'] === "search" ) {
				include 'qry_Shops.php';
				include 'qry_ShopsSearch.php';
			}
			
			include 'dsp_ShopsSearch.php';
		break;
		
		case "gift_setup":
            include("../lib/lib_Email.php");
			if($user_session->check())
			{
				$step = $_REQUEST['step']+0;
				if($step < 1 || 2 < $step)
					$step = 1;

				include("../lib/lib_Validation.php");
				include("val_GiftSetupLoggedIn{$step}.php");

				if($_POST['is_post'] && $validator->validate($_POST))
				{
					include("act_GiftSetupLoggedIn{$step}.php");
					if($ok) { header("Location: ".$redirect_url); exit; }
				}
				if($step == 1)
				{
					include("qry_GiftTypes.php");
					include("qry_Areas.php");
					include("qry_DefaultAddress.php");
				}
				include("dsp_GiftSetupLoggedIn{$step}.php");
			}
			else
			{
				include("../lib/lib_Validation.php");
				include("val_GiftSetup.php");

				if($_POST['is_post'] && $validator->validate($_POST))
				{
					include("act_GiftSetup.php");
					if($ok) { header("Location: ".$redirect_url); exit; }
				}


                include("qry_GiftTypes.php");
                include("qry_Areas.php");
				include("dsp_GiftSetup.php");
			}
			break;
		/*case "gift_setup":
			if($user_session->check())
			{
				$step = $_REQUEST['step']+0;
				if($step < 1 || 2 < $step)
					$step = 1;
					
				include("../lib/lib_Validation.php");
				include("val_GiftSetupLoggedIn{$step}.php");
				
				if($_POST['is_post'] && $validator->validate($_POST))
				{
					include("act_GiftSetupLoggedIn{$step}.php");
					if($ok) { header("Location: ".$redirect_url); exit; }
				}
				if($step == 1)
				{
					include("qry_GiftTypes.php");
					include("qry_Areas.php");
					include("qry_DefaultAddress.php");
				}
				include("dsp_GiftSetupLoggedIn{$step}.php");
			}
			else
			{
				$step = $_REQUEST['step']+0;
				if($step < 1 || 4 < $step)
					$step = 1;
					
				include("../lib/lib_Validation.php");
				include("val_GiftSetupStep{$step}.php");
				
				if($_POST['is_post'] && $validator->validate($_POST))
				{
					include("act_GiftSetupStep{$step}.php");
					if($ok) { header("Location: ".$redirect_url); exit; }
				}
				
				switch($step)
				{
					case 1:
						include("qry_GiftTypes.php");
						break;
					case 3:
						include("qry_Areas.php");
						break;
				}
				include("dsp_GiftSetupStep{$step}.php");
			}
			break;*/
			
		case "giftRegistry":
			if($user_session->check())
			{
				include("qry_GiftRegistry.php");
				include("dsp_GiftRegistry.php");
			}
			else
				header("Location: ".$config["dir"].'login');
			break;
			
		case "giftRegistryList":
			if($user_session->check())
			{
				include("qry_GiftRegistryList.php");
				include("dsp_GiftRegistryList.php");
			}
			else
				header("Location: ".$config["dir"].'login');
			break;
			
		case "editGiftRegistryListItemQuantity":
			if($user_session->check())
			{
				include("../lib/lib_Validation.php");
				include("val_GiftRegistryListItemQuantity.php");
				
				if($_POST['is_post'] && $validator->validate($_POST))
				{
					include("act_UpdateGiftRegistryListItemQuantity.php");
					include("dsp_EditGiftRegistryListItemQuantityStatus.php");
				}
				else
				{
					include("qry_GiftRegistryListItem.php");
					include("dsp_EditGiftRegistryListItemQuantity.php");
				}
			}
			else
			{
				if(isset($_REQUEST['ajax']))
					echo '<script language="javascript" type="text/javascript">/* <![CDATA[ */ $(document).ready(function(){ parent.$.fancybox.close(); }); /* ]]> */ </script>';
				else
					header("Location: ".$config["dir"]);
			}
			break;
			
		case "clearGiftRegistryListItemQuantity":
			if($user_session->check())
			{
				include("qry_GiftRegistryListItem.php");
				$_POST['quantity'] = $item['bought'];
				include("act_UpdateGiftRegistryListItemQuantity.php");
				header("Location: ".$config["dir"].'account/gift-registry/'.$_REQUEST['list_id']);
			}
			else
			{
				if(isset($_REQUEST['ajax']))
					echo '<script language="javascript" type="text/javascript">/* <![CDATA[ */ $(document).ready(function(){ parent.$.fancybox.close(); }); /* ]]> */ </script>';
				else
					header("Location: ".$config["dir"].'account/gift-registry/'.$_REQUEST['list_id']);
			}
			break;
			
		case "gift_lists":
			include("qry_GiftLists.php");
			include("dsp_GiftLists.php");
			break;
			
		case "gift_list":
			include("qry_GiftList.php");
			include("act_CheckCart.php");
			include("act_LastGiftList.php");
			include("dsp_GiftList.php");
			break;
			
		case "gift_list_confirmation":
			include("qry_GiftList.php");
			if($_POST['is_post'])
			{
				include("act_GiftListConfirmation.php");
				header("Location: ".$config["dir"].'gift-registry/list/'.$_REQUEST['code']);
				exit;
			}
			include("dsp_GiftListConfirmation.php");
			break;
		
		default:
			break;
	}
?>
