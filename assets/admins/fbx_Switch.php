<?
	/**
	 * e-Commerce System
	 * Copyright (c) 2002-2006 Philip John, All Rights Reserved.
	 * Author		: Philip John
	 * Version		: 6.0
	 * Modified By	: Marian Plesnicute
	 *
	 * PROPRIETARY/CONFIDENTIAL.  Use is subject to license terms.
	 */
?>
<?

	include("../lib/adodb/adodb.inc.php");
	include("../lib/act_OpenDB.php");
	include("../lib/lib_AdminSession.php");
	include("../lib/lib_ACL.php");
	include("../lib/lib_CommonAdmin.php");

	$session->check();

	if($session->getValue("siteid")=="")
	{
		$session->setValue("siteid",1);
		$session->save();
	}
	
	$results=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				shop_variables
			WHERE
				name IN ('vat','from')
		"
		)
	);
	while($row = $results->FetchRow())
	{
		if($row['name'] == 'vat')
			define('VAT', $row['value']);
		if($row['name'] == 'from')
			define('FROM', $row['value']);
	}
	
	$reason="";
	switch($Fusebox['fuseaction']) {

/********************************************************************************
 * Login
 *
 *
 ********************************************************************************/
		case "login":
			include("dsp_Login.php");
			break;

		case "doLogin":
			include("qry_Authenticate.php");
			if($auth)
			{
				$session->start($check->fields[0]);
				include("url_Login.php");
			}
			else
				include("url_AccessDenied.php");
			break;

		case "start":
			if($session->check())
			{
				$session->update();
				include("qry_Start.php");
				include("dsp_Start.php");
			}
			else
				include("url_AccessDenied.php");
			break;

		case "accessDenied":
			include("dsp_AccessDenied.php");
			break;

		case "sendPassword":
			if($_REQUEST['act']=="")
				include("dsp_SendPassword.php");
			else
			{
				include("qry_RetrievePassword.php");
				if($found)
				{
					include("../lib/lib_Email.php");
					include("act_SendPassword.php");
					include("dsp_PasswordSent.php");
				}
				else
				{
					$reason="That email address was not found in the database";
					include("dsp_SendPassword.php");
				}
			}
			break;

//Help

		case "help":
			if($session->check())
			{
				$session->update();
				include("../lib/lib_Help.php");
				if($_REQUEST['act']=="save")
					include("act_UpdateHelp.php");
				include("qry_Help.php");
				include("dsp_Help.php");
			}
			else
				include("url_AccessDenied.php");
			break;

//About

		case "about":
			if($session->check())
			{
				$session->update();
				include("dsp_About.php");
			}
			else
				include("url_AccessDenied.php");
			break;


/********************************************************************************
 * Orders
 *
 *
 ********************************************************************************/

//Orders
		case "orders":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("orders"))
				{
					if($_REQUEST['act']=="")
					{
						include("qry_Orders.php");
						include("dsp_Orders.php");
					}
					elseif($_REQUEST['act']=="export")
					{
						include("qry_OrdersExport.php");
						include("dsp_OrdersExport.php");
					}
					elseif($_REQUEST['act']=="print")
					{
						include("qry_OrdersPrint.php");
						include("dsp_OrdersPrint.php");
					}
				}
			}
			else
				include("url_AccessDenied.php");
			break;

		case "viewOrder":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("viewOrder"))
				{
					include("../lib/lib_Payment.php");
					include("../lib/payment/lib_".$config['psp']['driver'].".php");
					include("../lib/payment/cfg_".$config['psp']['driver'].".php");
					$psp =& new $config['psp']['driver']($config,$smarty,$db);
					include("qry_Order.php");
					include("dsp_Order.php");
				}
			}
			else
				include("url_AccessDenied.php");
			break;

		case "refundOrder":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("refundOrder"))
				{
					/*if($_REQUEST['act']=="")
					{
						include("qry_Order.php");
						include("dsp_RefundOrder.php");
					}
					elseif($_REQUEST['act']=="refund")
					{
						include("../lib/lib_Payment.php");
						include("../lib/payment/lib_".$config['psp']['driver'].".php");
						include("../lib/payment/cfg_".$config['psp']['driver'].".php");
						$psp =& new $config['psp']['driver']($config,$smarty,$db);
						
						include("qry_Order.php");
						include("act_RefundOrder.php");
						if($ok) { header("location: ".$config['dir']."index.php?fuseaction=admin.".($order['processed']?'viewRecord':'viewOrder')."&order_id=".$order['id']); exit; }
					}*/
					include("../lib/lib_Payment.php");
					include("../lib/payment/lib_".$config['psp']['driver'].".php");
					include("../lib/payment/cfg_".$config['psp']['driver'].".php");
					$psp =& new $config['psp']['driver']($config,$smarty,$db);
						
					include("qry_Order.php");
					include("act_RefundOrder.php");
					
					if($ok) { header("location: ".$config['dir']."index.php?fuseaction=admin.".($order['processed']?'viewRecord':'viewOrder')."&order_id=".$order['id']); exit; }
					
					if($_REQUEST['act']=="")
					{
						//include("qry_Order.php");
						include("dsp_RefundOrder.php");
					}
				}			
			}
			else
				include("url_AccessDenied.php");
			break;
			
		/*case "refundOrderProduct":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("refundOrder"))
				{
					include("../lib/lib_Payment.php");
					include("../lib/payment/lib_".$config['psp']['driver'].".php");
					include("../lib/payment/cfg_".$config['psp']['driver'].".php");
					$psp =& new $config['psp']['driver']($config,$smarty,$db);
					
					include("qry_RefundOrderProduct.php");
					include("act_RefundOrderProduct.php");
					include("dsp_RefundOrderProduct.php");									
				}			
			}
			else
				include("url_AccessDenied.php");
			break;*/
			
		case "processOrder":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("processOrder"))
				{
					//Get order details and send confirmation of processing
					include("../lib/lib_Payment.php");
					include("../lib/payment/lib_".$config['psp']['driver'].".php");
					include("../lib/payment/cfg_".$config['psp']['driver'].".php");
					$psp =& new $config['psp']['driver']($config,$smarty,$db);
					include("qry_Order.php");
					include("../lib/lib_Email.php");
					include("act_SendProcessedNotification.php");

					//Process the order
					include("act_ProcessOrder.php");
					if($ok) { header("location: ".$config['dir']."index.php?fuseaction=admin.orders"); exit; }
				}
			}
			else
				include("url_AccessDenied.php");
			break;
			
		case "dispatchOrder":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("dispatchOrder"))
				{
					include("../lib/lib_Email.php");
					include("qry_Order.php");
					include("act_DispatchOrder.php");
					if($ok) { header("location: ".$config['dir']."index.php?fuseaction=admin.records"); exit; }
				}			
			}
			else
				include("url_AccessDenied.php");
			break;
			
			
		case "workflow":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("workflow"))
				{
					if($_REQUEST['act']=="")
					{
						include("qry_Workflow.php");
						if($ok)
							include("dsp_Workflow.php");
					}
					elseif($_REQUEST['act']=="dispatch")
					{
						include("../lib/lib_Email.php");
						include("qry_DispatchOrders.php");
						include("act_DispatchOrders.php");
						if($ok) { header("location: ".$config['dir']."index.php?fuseaction=admin.records"); exit; }
					}
				}
			}
			else
				include("url_AccessDenied.php");
			break;
			
		case "papHistory":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("papHistory"))
				{
					include("qry_PapHistory.php");
					include("dsp_PapHistory.php");
				}
			}
			else
				include("url_AccessDenied.php");
			break;
			
//Records
		case "records":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("records"))
				{
					if($_REQUEST['act']=="")
					{
						include("qry_Records.php");
						include("dsp_Records.php");
					}
					elseif($_REQUEST['act']=="export")
					{
						include("qry_RecordsExport.php");
						include("dsp_RecordsExport.php");
					}
					elseif($_REQUEST['act']=="export_products")
					{
						include("qry_ProductsExport.php");
						include("dsp_ProductsExport.php");
					}
					elseif($_REQUEST['act']=="print")
					{
						include("qry_RecordsPrint.php");
						include("dsp_RecordsPrint.php");
					}
				}
			}
			else
				include("url_AccessDenied.php");
			break;

		case "viewRecord":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("viewRecord"))
				{
					include("../lib/lib_Payment.php");
					include("../lib/payment/lib_".$config['psp']['driver'].".php");
					include("../lib/payment/cfg_".$config['psp']['driver'].".php");
					$psp =& new $config['psp']['driver']($config,$smarty,$db);
					include("qry_Record.php");
					include("dsp_Record.php");
				}
			}
			else
				include("url_AccessDenied.php");
			break;

//Search
		case "search":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("search"))
				{
					if($_REQUEST['act']=="")
						include("dsp_SearchOrders.php");
					else
					{
						include("qry_SearchOrders.php");
						include("dsp_SearchOrdersResults.php");
					}
				}
			}
			else
				include("url_AccessDenied.php");
			break;

/********************************************************************************
 * Products & Stock
 *
 *
 ********************************************************************************/

//Products
		case "products":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("products"))
				{
					include("qry_Categories.php");
					include("qry_Products.php");
					include("dsp_Products.php");
				}
			}
			else
				include("url_AccessDenied.php");
			break;

		case "orderProduct":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("products"))
				{
					include("act_OrderProduct.php");
					if($ok) { header("location: ".$config['dir']."index.php?fuseaction=admin.products&category_id=".$_REQUEST['category_id']); exit; }
				}
			}
			else
				include("url_AccessDenied.php");
			break;
			
		case "sortProduct":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("orderProduct"))
				{
					include("act_SortProduct.php");
					exit;
				}
			}
			else
				include("url_AccessDenied.php");
			break;

		case "move":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("move"))
				{
					if($_REQUEST['act']=="")
					{
						include("qry_Categories.php");
						include("dsp_MoveProduct.php");
					}
					else
					{
						include("act_MoveProduct.php");
						if($ok) { header("location: ".$config['dir']."index.php?fuseaction=admin.products&category_id=".$_REQUEST['category_id']); exit; }
					}
				}
			}
			else
				include("url_AccessDenied.php");
			break;

		case "addProduct":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("addProduct"))
				{
					if($_REQUEST['act']=="")
					{
						include("../lib/lib_WYSIWYG.php");
						include("qry_FittingGuides.php");
						include("qry_Areas.php");
						include("qry_Brands.php");
						include("qry_Category.php");
						include("qry_AllCategoryFilters.php");
						include("qry_Options.php");
						include("dsp_AddProduct.php");
					}
					else if($_REQUEST['act']=="add")
					{
						include("../VLib/lib_VImage.php");
                        include("../VLib/VImage/cls_".$vcfg['vimage']['cls'].".php");
						include("../VLib/lib_Sitemap.php");
						include("../lib/lib_Search.php");
						include("act_AddProduct.php");
						if($ok) { header("location: ".$config['dir']."index.php?fuseaction=admin.addProduct&act=images&product_id=".$product_id."&category_id=".$_POST['category_id']); exit; }
					}
					else if($_REQUEST['act']=="images")
					{
						include("qry_AllColors.php");
						include("qry_ProductImages.php");
                        include("qry_ImageSizes.php");
						include("dsp_AddProductImages.php");
					}
					else if($_REQUEST['act']=="addImage")
					{
						include("../VLib/lib_VImage.php");
                        include("../VLib/VImage/cls_".$vcfg['vimage']['cls'].".php");
						include("act_AddProductImage.php");
						if($ok) { header("location: ".$config['dir']."index.php?fuseaction=admin.addProduct&act=images&product_id=".$_POST['product_id']."&category_id=".$_POST['category_id']); exit; }
					}
					else if($_REQUEST['act']=="addCroppedImages")
					{
                        include("../VLib/lib_VImage.php");
                        include("../VLib/VImage/cls_".$vcfg['vimage']['cls'].".php");
                        include("qry_ImageSizes.php");
						include("act_AddProductCroppedImages.php");
						if($ok) { header("location: ".$config['dir']."index.php?fuseaction=admin.addProduct&act=images&product_id=".$_POST['product_id']."&category_id=".$_POST['category_id']); exit; }
					}
					else if($_REQUEST['act']=="reuploadImage")
					{
						include("qry_ProductImage.php");
                        include("qry_ImageSizes.php");
						include("dsp_ReuploadProductImages.php");
					}
					else if($_REQUEST['act']=="editCroppedImages")
					{
                        include("../VLib/lib_VImage.php");
                        include("../VLib/VImage/cls_".$vcfg['vimage']['cls'].".php");
                        include("qry_ImageSizes.php");
						include("qry_ProductImage.php");
						include("act_UpdateProductCroppedImages.php");
						if($ok) { header("location: ".$config['dir']."index.php?fuseaction=admin.addProduct&act=images&product_id=".$_POST['product_id']."&category_id=".$_POST['category_id']); exit; }
					}
					else if($_REQUEST['act']=="removeImage")
					{
						include("act_RemoveProductImage.php");
						if($ok) { header("location: ".$config['dir']."index.php?fuseaction=admin.addProduct&act=images&product_id=".$_POST['product_id']."&category_id=".$_POST['category_id']); exit; }
					}
					else if($_REQUEST['act']=="remove360Image")
					{
						include("act_RemoveProduct360Image.php");
						if($ok) { header("location: ".$config['dir']."index.php?fuseaction=admin.addProduct&act=images&product_id=".$_POST['product_id']."&category_id=".$_POST['category_id']); exit; }
					}
					else if($_REQUEST['act']=="colorImage")
					{
						include("act_UpdateColorImage.php");
						if($ok) { header("location: ".$config['dir']."index.php?fuseaction=admin.addProduct&act=images&product_id=".$_POST['product_id']."&category_id=".$_POST['category_id']); exit; }
					}
					else
					{
						header("location: ".$config['dir']."index.php?fuseaction=admin.products&category_id=".$_REQUEST['category_id']); 
						exit;
					}
				}
			}
			else
				include("url_AccessDenied.php");
			break;

		case "editProduct":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("editProduct"))
				{
					if($_REQUEST['act']=="")
					{
						include("../lib/lib_WYSIWYG.php");
						include("qry_FittingGuides.php");
						include("qry_Brands.php");
						include("qry_Product.php");
						include("qry_AllCategoryFilters.php");
						include("qry_Options.php");
						if($_REQUEST['full'])
							include("dsp_EditProductFull.php");
						else
							include("dsp_EditProduct.php");
					}
					else if($_REQUEST['act']=="save")
					{
						include("../VLib/lib_VImage.php");
                        include("../VLib/VImage/cls_".$vcfg['vimage']['cls'].".php");
						include("../VLib/lib_Sitemap.php");
						include("../lib/lib_Search.php");
						include("qry_UpdateProduct.php");
						include("act_UpdateProduct.php");
						if($ok) { header("location: ".$config['dir']."index.php?fuseaction=admin.editProduct&act=images&product_id=".$_POST['product_id']."&category_id=".$_POST['category_id']); exit; }
					}
					else if($_REQUEST['act']=="images")
					{
						include("qry_AllColors.php");
						include("qry_ProductImages.php");
						include("qry_ImageSizes.php");
						include("dsp_EditProductImages.php");
					}
					else if($_REQUEST['act']=="addImage")
					{
						include("../VLib/lib_VImage.php");
                        include("../VLib/VImage/cls_".$vcfg['vimage']['cls'].".php");
						include("act_AddProductImage.php");
						if($ok) { header("location: ".$config['dir']."index.php?fuseaction=admin.editProduct&act=images&product_id=".$_POST['product_id']."&category_id=".$_POST['category_id']); exit; }
					}
					else if($_REQUEST['act']=="addCroppedImages")
					{
                        include("../VLib/lib_VImage.php");
                        include("../VLib/VImage/cls_".$vcfg['vimage']['cls'].".php");
                        include("qry_ImageSizes.php");
						include("act_AddProductCroppedImages.php");
						if($ok) { header("location: ".$config['dir']."index.php?fuseaction=admin.editProduct&act=images&product_id=".$_POST['product_id']."&category_id=".$_POST['category_id']); exit; }
					}
					else if($_REQUEST['act']=="reuploadImage")
					{
						include("qry_ProductImage.php");
                        include("qry_ImageSizes.php");
						include("dsp_ReuploadProductImages.php");
					}
					else if($_REQUEST['act']=="editCroppedImage")
					{
						include("qry_ProductImage.php");
						include("act_RecropProductImage.php");
					}
					else if($_REQUEST['act']=="editCroppedImages")
					{
                        include("../VLib/lib_VImage.php");
                        include("../VLib/VImage/cls_".$vcfg['vimage']['cls'].".php");
                        include("qry_ImageSizes.php");
						include("qry_ProductImage.php");
						include("act_UpdateProductCroppedImages.php");
						if($ok) { header("location: ".$config['dir']."index.php?fuseaction=admin.editProduct&act=images&product_id=".$_POST['product_id']."&category_id=".$_POST['category_id']); exit; }
					}
					else if($_REQUEST['act']=="removeImage")
					{
						include("act_RemoveProductImage.php");
						if($ok) { header("location: ".$config['dir']."index.php?fuseaction=admin.editProduct&act=images&product_id=".$_POST['product_id']."&category_id=".$_POST['category_id']); exit; }
					}
					else if($_REQUEST['act']=="remove360Image")
					{
						include("act_RemoveProduct360Image.php");
						if($ok) { header("location: ".$config['dir']."index.php?fuseaction=admin.editProduct&act=images&product_id=".$_POST['product_id']."&category_id=".$_POST['category_id']); exit; }
					}
					else if($_REQUEST['act']=="update_image")
					{
						include("act_UpdateProductImage.php");
						if($ok) { header("location: ".$config['dir']."index.php?fuseaction=admin.editProduct&act=images&product_id=".$_REQUEST['product_id']."&category_id=".$_REQUEST['category_id']); exit; }
					}
					else if($_REQUEST['act']=="update_images")
					{
						include("act_UpdateProductImages.php");
						if($ok) { header("location: ".$config['dir']."index.php?fuseaction=admin.editProduct&act=images&product_id=".$_REQUEST['product_id']."&category_id=".$_REQUEST['category_id']); exit; }
					}
					else if($_REQUEST['act']=="colorImage")
					{
						include("act_UpdateColorImage.php");
						if($ok) { header("location: ".$config['dir']."index.php?fuseaction=admin.editProduct&act=images&product_id=".$_POST['product_id']."&category_id=".$_POST['category_id']); exit; }
					}
					else
					{
						header("location: ".$config['dir']."index.php?fuseaction=admin.products&category_id=".$_REQUEST['category_id']); 
						exit;
					}
				}
			}
			else
				include("url_AccessDenied.php");
			break;

		case "deleteProduct":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("deleteProduct"))
				{
					include("../VLib/lib_Sitemap.php");
					include("../lib/lib_Search.php");
					include("act_RemoveProduct.php");
					if($ok) { header("location: ".$config['dir']."index.php?fuseaction=admin.products&category_id=".$_REQUEST['category_id']); exit; }
				}
			}
			else
				include("url_AccessDenied.php");
			break;

		case "productState":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("productState"))
				{
					include("act_ProductState.php");
					if($ok) { header("location: ".$config['dir']."index.php?fuseaction=admin.products&category_id=".$_REQUEST['category_id']); exit; }
				}
			}
			else
				include("url_AccessDenied.php");
			break;
			
		case "makeCopy":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("makeCopy"))
				{
					include("act_AddCopy.php");
					if($ok) { header("location: ".$config['dir']."index.php?fuseaction=admin.products&category_id=".$_REQUEST['category_id']); exit; }
				}
			}
			else
				include("url_AccessDenied.php");
			break;
			
		case "deleteCopy":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("deleteCopy"))
				{
					include("act_RemoveCopy.php");
					if($ok) { header("location: ".$config['dir']."index.php?fuseaction=admin.products&category_id=".$_REQUEST['category_id']); exit; }
				}
			}
			else
				include("url_AccessDenied.php");
			break;

		case "unlinkCopy":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("unlinkCopy"))
				{
					include("act_UnlinkCopy.php");
					if($ok) { header("location: ".$config['dir']."index.php?fuseaction=admin.products&category_id=".$_REQUEST['category_id']); exit; }
				}
			}
			else
				include("url_AccessDenied.php");
			break;

		case "makeReference":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("makeReference"))
				{
					include("act_AddReference.php");
					if($ok) { header("location: ".$config['dir']."index.php?fuseaction=admin.products&category_id=".$_REQUEST['category_id']); exit; }
				}
			}
			else
				include("url_AccessDenied.php");
			break;
			
		case "deleteReference":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("deleteReference"))
				{
					include("act_RemoveReference.php");
					if($ok) { header("location: ".$config['dir']."index.php?fuseaction=admin.products&category_id=".$_REQUEST['category_id']); exit; }
				}
			}
			else
				include("url_AccessDenied.php");
			break;

		case "massMove":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("massMove"))
				{
					if($_REQUEST['act']=="")
					{
						include("qry_MassMove.php");
						include("qry_Categories.php");
						include("dsp_MassMove.php");
					}
					else if($_REQUEST['act']=="move")
					{
						include("qry_MassMove.php");
						include("dsp_ConfirmMassMove.php");
					}
					else if($_REQUEST['act']=="confirm")
					{
						include("act_MassMove.php");
						if($ok) { header("location: ".$config['dir']."index.php?fuseaction=admin.massMove&act=done&category_id=".$_REQUEST['category_id']); exit; }
					}
					else if($_REQUEST['act']=="done")
					{
						include("dsp_MassMoveDone.php");
					}
				}
			}
			else
				include("url_AccessDenied.php");
			break;

		case "massDelete":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("massDelete"))
				{
					if($_REQUEST['act']=="")
					{
						include("qry_MassDelete.php");
						include("dsp_ConfirmMassDelete.php");
					}
					else if($_REQUEST['act']=="confirm")
					{
						include("act_MassDelete.php");
						if($ok) { header("location: ".$config['dir']."index.php?fuseaction=admin.massDelete&act=done&category_id=".$_REQUEST['category_id']); exit; }
					}
					else if($_REQUEST['act']=="done")
					{
						include("dsp_MassDelete.php");
					}
				}
			}
			else
				include("url_AccessDenied.php");
			break;
			
		case "productSearch":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("products"))
				{
					include("../lib/lib_Search.php");
					include("qry_ProductSearch.php");
					include("dsp_ProductSearch.php");
				}
			}
			else
				include("url_AccessDenied.php");
			break;

/********************************************************************************
 * Categories
 *
 *
 ********************************************************************************/

		case "categories":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("categories"))
				{
					include("qry_Categories.php");
					include("dsp_Categories.php");
				}
			}
			else
				include("url_AccessDenied.php");
			break;

		case "moveCategory":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("moveCategory"))
				{
					if($_REQUEST['act']=="")
					{
						include("qry_MoveCategory.php");
						include("dsp_MoveCategory.php");
					}
					else
					{
						include("../lib/lib_DBTree.php");
						
						include("act_MoveCategory.php");
						include("act_UpdateTrail.php");
						if($ok) { header("location: ".$config['dir']."index.php?fuseaction=admin.categories&category_id=".$category_id); exit; }
					}
				}
			}
			else
				include("url_AccessDenied.php");
			break;

		case "addCategory":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("addCategory"))
				{
					if($_REQUEST['act']=="")
					{
						include("../lib/lib_WYSIWYG.php");
						include("qry_Areas.php");
						include("qry_GroupedCategories.php");
						include("dsp_AddCategory.php");
					}
					else
					{
						include("../lib/lib_Resize.php");
						include("../lib/lib_DBTree.php");
	
						include("act_AddCategory.php");
						include("act_UpdateTrail.php");
						if($ok) { header("location: ".$config['dir']."index.php?fuseaction=admin.categories&category_id=".$category_id); exit; }
					}
				}
			}
			else
				include("url_AccessDenied.php");
			break;

		case "editCategory":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("editCategory"))
				{
					if($_REQUEST['act']=="")
					{
						include("../lib/lib_WYSIWYG.php");
						include("qry_FittingGuides.php");
						include("qry_GroupedCategories.php");
						include("qry_GoogleCategories.php");
						include("qry_Category.php");
						include("dsp_EditCategory.php");
					}
					else
					{
						include("../lib/lib_Resize.php");
						include("qry_UpdateCategory.php");
						include("act_UpdateCategory.php");
						include("act_UpdateTrail.php");
						if($ok) { header("location: ".$config['dir']."index.php?fuseaction=admin.categories&category_id=".$category_id); exit; }
					}
				}
			}
			else
				include("url_AccessDenied.php");
			break;

		case "removeCategory":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("removeCategory"))
				{
					include("../lib/lib_DBTree.php");
					
					include("act_RemoveCategory.php");
					include("act_UpdateTrail.php");
					if($ok) { header("location: ".$config['dir']."index.php?fuseaction=admin.categories&category_id=".$category_id); exit; }
				}
			}
			else
				include("url_AccessDenied.php");
			break;

		case "orderCategory":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("orderCategory"))
				{
					include("../lib/lib_DBTree.php");
					
					include("act_OrderCategory.php");
					if($ok) { header("location: ".$config['dir']."index.php?fuseaction=admin.categories&category_id=".$category_id); exit; }
				}
			}
			else
				include("url_AccessDenied.php");
			break;
			
// Highlighted Products
		case "categoryBoxes":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("categoryBoxes"))
				{
					include("qry_CategoryBoxes.php");
					include("dsp_CategoryBoxes.php");
				}
			}
			else
				include("url_AccessDenied.php");
			break;
			
		case "addCategoryBox":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("addCategoryBox"))
				{
					if($_REQUEST['act']=="")
					{
						include("dsp_AddCategoryBox.php");
					}
					else
					{
						include("act_AddCategoryBox.php");
						if($ok) { header("location: ".$config['dir']."index.php?fuseaction=admin.categoryBoxes&category_id=".$_REQUEST['category_id']); exit; }
					}
				}
			}
			else
				include("url_AccessDenied.php");
			break;
			
		case "removeCategoryBox":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("removeCategoryBox"))
				{
					include("qry_CategoryBoxItems.php");
					include("act_RemoveCategoryBox.php");
					if($ok) { header("location: ".$config['dir']."index.php?fuseaction=admin.categoryBoxes&category_id=".$_REQUEST['category_id']); exit; }
				}
			}
			else
				include("url_AccessDenied.php");
			break;
			
		case "orderCategoryBox":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("orderCategoryBox"))
				{
					if($_REQUEST['dir']=="up")
						include("act_MoveCategoryBoxUp.php");
					else
						include("act_MoveCategoryBoxDown.php");
					header("location: ".$config['dir']."index.php?fuseaction=admin.categoryBoxes&category_id=".$_REQUEST['category_id']);
				}
			}
			else
				include("url_AccessDenied.php");
			break;
			
		case "categoryBoxItems":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("categoryBoxItems"))
				{
					include("qry_CategoryBoxItems.php");
					include("dsp_CategoryBoxItems.php");
				}
			}
			else
				include("url_AccessDenied.php");
			break;
			
		case "editCategoryBoxItem":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("editCategoryBoxItem"))
				{
					if($_REQUEST['act']=="")
					{
						include("qry_CategoryProducts.php");
						include("qry_CategoryBoxItem.php");
						include("dsp_EditCategoryBoxItem.php");
					}
					else
					{
						include("qry_CategoryBoxItem.php");
						include("act_UpdateCategoryBoxItem.php");
						if($ok) { header("location: ".$config['dir']."index.php?fuseaction=admin.categoryBoxItems&box_id=".$_REQUEST['box_id']."&category_id=".$_REQUEST['category_id']); exit; }
					}
				}
			}
			else
				include("url_AccessDenied.php");
			break;
			
// Category Filters
		case "categoryFilters":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("categoryFilters"))
				{
					include("qry_CategoryFilters.php");
					include("dsp_CategoryFilters.php");
				}
			}
			else
				include("url_AccessDenied.php");
			break;
			
		case "addCategoryFilter":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("addCategoryFilter"))
				{
					if($_REQUEST['act']=="")
					{
						include("dsp_AddCategoryFilter.php");
					}
					else
					{
						include("act_AddCategoryFilter.php");
						if($ok) { header("location: ".$config['dir']."index.php?fuseaction=admin.categoryFilters&category_id=".$_REQUEST['category_id']); exit; }
					}
				}
			}
			else
				include("url_AccessDenied.php");
			break;
			
		case "editCategoryFilter":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("editCategoryFilter"))
				{
					if($_REQUEST['act']=="")
					{
						include("qry_CategoryFilter.php");
						include("dsp_EditCategoryFilter.php");
					}
					else
					{
						include("act_UpdateCategoryFilter.php");
						if($ok) { header("location: ".$config['dir']."index.php?fuseaction=admin.categoryFilters&category_id=".$_REQUEST['category_id']); exit; }
					}
				}
			}
			else
				include("url_AccessDenied.php");
			break;
			
		case "removeCategoryFilter":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("removeCategoryFilter"))
				{
					include("act_RemoveCategoryFilter.php");
					if($ok) { header("location: ".$config['dir']."index.php?fuseaction=admin.categoryFilters&category_id=".$_REQUEST['category_id']); exit; }
				}
			}
			else
				include("url_AccessDenied.php");
			break;
			
		case "orderCategoryFilter":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("orderCategoryFilter"))
				{
					if($_REQUEST['dir']=="up")
						include("act_MoveCategoryFilterUp.php");
					else
						include("act_MoveCategoryFilterDown.php");
					header("location: ".$config['dir']."index.php?fuseaction=admin.categoryFilters&category_id=".$_REQUEST['category_id']);
				}
			}
			else
				include("url_AccessDenied.php");
			break;
/*
		case "categoryFilterItems":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("categoryFilterItems"))
				{
					include("qry_CategoryFilterItems.php");
					include("dsp_CategoryFilterItems.php");
				}
			}
			else
				include("url_AccessDenied.php");
			break;
			
		case "editCategoryFilterItem":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("editCategoryFilterItem"))
				{
					if($_REQUEST['act']=="")
					{
						include("qry_CategoryProducts.php");
						include("qry_CategoryFilterItem.php");
						include("dsp_EditCategoryFilterItem.php");
					}
					else
					{
						include("qry_CategoryFilterItem.php");
						include("act_UpdateCategoryFilterItem.php");
						if($ok) { header("location: ".$config['dir']."index.php?fuseaction=admin.categoryFilterItems&box_id=".$_REQUEST['box_id']."&category_id=".$_REQUEST['category_id']); exit; }
					}
				}
			}
			else
				include("url_AccessDenied.php");
			break;
*/

/********************************************************************************
 * Stock, Brands and Suppliers
 *
 *
 ********************************************************************************/

		case "stock":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("stock"))
				{
					if($_REQUEST['act']=="")
					{
						include("qry_Stock.php");
						include("dsp_Stock.php");
					}
					else
					{
						include("act_UpdateStock.php");
						if($ok) { header("location: ".$config['dir']."index.php?fuseaction=admin.stock"); exit; }
					}
				}
			}
			else
				include("url_AccessDenied.php");
			break;

//Brands
		case "brands":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("brands"))
				{
					include("qry_Brands.php");
					include("dsp_Brands.php");
				}
			}
			else
				include("url_AccessDenied.php");
			break;

		case "addBrand":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("addBrand"))
				{
					if($_REQUEST['act']=="")
					{
						include("../lib/lib_WYSIWYG.php");
						include("qry_Suppliers.php");
						include("dsp_AddBrand.php");
					}
					else
					{
						include("../lib/lib_Resize.php");
						include("act_AddBrand.php");
						if($ok) { header("location: ".$config['dir']."index.php?fuseaction=admin.brands"); exit; }
					}
				}
			}
			else
				include("url_AccessDenied.php");
			break;

		case "editBrand":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("editBrand"))
				{
					if($_REQUEST['act']=="")
					{
						include("../lib/lib_WYSIWYG.php");
						include("qry_Suppliers.php");
						include("qry_BrandDetails.php");
						include("dsp_EditBrand.php");
					}
					else
					{
						include("../lib/lib_Resize.php");
						include("act_UpdateBrand.php");
						if($ok) { header("location: ".$config['dir']."index.php?fuseaction=admin.brands"); exit; }
					}
				}
			}
			else
				include("url_AccessDenied.php");
			break;

		case "removeBrand":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("removeBrand"))
				{
					include("act_RemoveBrand.php");
					if($ok) { header("location: ".$config['dir']."index.php?fuseaction=admin.brands"); exit; }
				}
			}
			else
				include("url_AccessDenied.php");
			break;

//Suppliers
		case "suppliers":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("suppliers"))
				{
					include("qry_Suppliers.php");
					include("dsp_Suppliers.php");
				}
			}
			else
				include("url_AccessDenied.php");
			break;

		case "addSupplier":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("addSupplier"))
				{
					if($_REQUEST['act']=="")
					{
						include("qry_Countries.php");
						include("dsp_AddSupplier.php");
					}
					else
					{
						include("act_AddSupplier.php");
						if($ok) { header("location: ".$config['dir']."index.php?fuseaction=admin.suppliers"); exit; }
					}
				}
			}
			else
				include("url_AccessDenied.php");
			break;

		case "editSupplier":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("editSupplier"))
				{
					if($_REQUEST['act']=="")
					{
						include("qry_Countries.php");
						include("qry_Supplier.php");
						include("dsp_EditSupplier.php");
					}
					else
					{
						include("act_UpdateSupplier.php");
						if($ok) { header("location: ".$config['dir']."index.php?fuseaction=admin.suppliers"); exit; }
					}
				}
			}
			else
				include("url_AccessDenied.php");
			break;

		case "removeSupplier":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("removeSupplier"))
				{
					include("act_RemoveSupplier.php");
					if($ok) { header("location: ".$config['dir']."index.php?fuseaction=admin.suppliers"); exit; }
				}
			}
			else
				include("url_AccessDenied.php");
			break;

/********************************************************************************
 * Shipping
 *
 *
 ********************************************************************************/

//Rules
		case "rules":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("rules"))
				{
					if($_REQUEST['act']=="")
					{
						include("dsp_Rules.php");
					}
					else
					{
						include("../lib/lib_Compiler.php");
						include("act_UpdateRules.php");
						if($ok) { header("location: ".$config['dir']."index.php?fuseaction=admin.rules"); exit; }
					}
				}
			}
			else
				include("url_AccessDenied.php");
			break;

//Test Rules
		case "testRules":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("testRules"))
				{
					include("qry_Countries.php");
					include("qry_Areas.php");
					if($_REQUEST['act']=="")
					{
						include("dsp_TestRules.php");
					}
					else
					{
						include("../lib/lib_Executor.php");
						include("qry_TestRules.php");
						include("dsp_TestResults.php");
					}
				}
			}
			else
				include("url_AccessDenied.php");
			break;

//Areas
		case "areas":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("areas"))
				{
					include("qry_Areas.php");
					include("dsp_Areas.php");
				}
			}
			else
				include("url_AccessDenied.php");
			break;

		case "addArea":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("addArea"))
				{
					if($_REQUEST['act']=="")
						include("dsp_AddArea.php");
					else
					{
						include("act_AddArea.php");
						if($ok) { header("location: ".$config['dir']."index.php?fuseaction=admin.areas"); exit; }
					}
				}
			}
			else
				include("url_AccessDenied.php");
			break;

		case "editArea":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("editArea"))
				{
					if($_REQUEST['act']=="")
					{
						include("qry_AreaDetails.php");
						include("dsp_EditArea.php");
					}
					else
					{
						include("act_UpdateArea.php");
						if($ok) { header("location: ".$config['dir']."index.php?fuseaction=admin.areas"); exit; }
					}
				}
			}
			else
				include("url_AccessDenied.php");
			break;

		case "removeArea":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("removeArea"))
				{
					include("act_RemoveArea.php");
					if($ok) { header("location: ".$config['dir']."index.php?fuseaction=admin.areas"); exit; }
				}
			}
			else
				include("url_AccessDenied.php");
			break;

//Countries
		case "countries":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("countries"))
				{
					include("qry_Countries.php");
					include("dsp_Countries.php");
				}
			}
			else
				include("url_AccessDenied.php");
			break;

		case "addCountry":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("addCountry"))
				{
					if($_REQUEST['act']=="")
					{
						include("qry_Areas.php");
						include("dsp_AddCountry.php");
					}
					else
					{
						include("act_AddCountry.php");
						if($ok) { header("location: ".$config['dir']."index.php?fuseaction=admin.countries&area_id=".$_REQUEST['area_id']); exit; }
					}
				}
			}
			else
				include("url_AccessDenied.php");
			break;

		case "editCountry":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("editCountry"))
				{
					if($_REQUEST['act']=="")
					{
						include("qry_Areas.php");
						include("qry_Country.php");
						include("dsp_EditCountry.php");
					}
					else
					{
						include("act_UpdateCountry.php");
						if($ok) { header("location: ".$config['dir']."index.php?fuseaction=admin.countries&area_id=".$_REQUEST['area_id']); exit; }
					}
				}
			}
			else
				include("url_AccessDenied.php");
			break;

		case "removeCountry":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("removeCountry"))
				{
					include("act_RemoveCountry.php");
					if($ok) { header("location: ".$config['dir']."index.php?fuseaction=admin.countries&area_id=".$_REQUEST['area_id']); exit; }
				}
			}
			else
				include("url_AccessDenied.php");
			break;

/********************************************************************************
 * Shop Data
 *
 *
 ********************************************************************************/

//Product Feed
		case "feed":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("feed"))
				{
					include("qry_DataFeeds.php");
					include("dsp_DataFeeds.php");
				}
			}
			else
				include("url_AccessDenied.php");
			break;

		case "publishFeed":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("publishFeed"))
				{
					include("../lib/lib_DataFeed.php");
					include("qry_DataFeed.php");
					include("act_PublishFeed.php");
					include("dsp_PublishFeed.php");
				}
			}
			else
				include("url_AccessDenied.php");
			break;
//Stats
		case "reports":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("reports"))
				{
					if($_REQUEST['act']=="")
					{
						include("qry_Reports.php");
						include("dsp_Reports.php");
					}
					elseif($_REQUEST['act']=="export")
					{
						include("qry_Reports.php");
						include("dsp_ReportsExport.php");
					}
					elseif($_REQUEST['act']=="full_export")
					{
						include("qry_ReportsFullExport.php");
						include("dsp_ReportsFullExport.php");
					}
				}
			}
			else
				include("url_AccessDenied.php");
			break;

		case "webOrders":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("orders"))
				{
					include("qry_ExportOrders.php");
					include("dsp_ExportOrders.php");
				}
			}
			else
				include("url_AccessDenied.php");
			break;
			
		case "webUsers":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("users"))
				{
					include("qry_ExportUsers.php");
					include("dsp_ExportUsers.php");
				}
			}
			else
				include("url_AccessDenied.php");
			break;
			
		case "webStock":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("products"))
				{
					include("qry_ExportStock.php");
					include("dsp_ExportStock.php");
				}
			}
			else
				include("url_AccessDenied.php");
			break;
			
		case "sales_reports":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("sales_reports"))
				{
					if($_REQUEST['act']=="")
					{
						include("qry_SalesReports.php");
						include("dsp_SalesReports.php");
					}
					elseif($_REQUEST['act']=="export")
					{
						include("qry_SalesReports.php");
						include("dsp_SalesReportsExport.php");
					}
				}
			}
			else
				include("url_AccessDenied.php");
			break;
			
		case "voucher_reports":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("voucher_reports"))
				{
					if($_REQUEST['act']=="")
					{
						include("qry_VoucherReports.php");
						include("dsp_VoucherReports.php");
					}
					elseif($_REQUEST['act']=="export")
					{
						include("qry_VoucherReportsExport.php");
						include("dsp_VoucherReportsExport.php");
					}
				}
			}
			else
				include("url_AccessDenied.php");
			break;
			
		case "commission_reports":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("commission_reports"))
				{
					if($_REQUEST['act']=="")
					{
						include("qry_CommissionReports.php");
						include("dsp_CommissionReports.php");
					}
					elseif($_REQUEST['act']=="export")
					{
						include("qry_CommissionReportsExport.php");
						include("dsp_CommissionReportsExport.php");
					}
				}
			}
			else
				include("url_AccessDenied.php");
			break;
/********************************************************************************
 * Content
 *
 *
 ********************************************************************************/

//Layouts
		case "layouts":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("layouts"))
				{
					include("qry_Layouts.php");
					include("dsp_Layouts.php");
				}
			}
			else
				include("url_AccessDenied.php");
			break;

		case "addLayout":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("addLayout"))
				{
					if($_REQUEST['act']=="")
						include("dsp_AddLayout.php");
					else
					{
						include("act_AddLayout.php");
						if($ok) header("location: ".$config['dir']."index.php?fuseaction=admin.layouts");
					}
				}
			}
			else
				include("url_AccessDenied.php");
			break;

		case "editLayout":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("editLayout"))
				{
					if($_REQUEST['act']=="")
					{
						include("qry_Layout.php");
						include("dsp_EditLayout.php");
					}
					else
					{
						include("act_UpdateLayout.php");
						if($ok) header("location: ".$config['dir']."index.php?fuseaction=admin.layouts");
					}
				}
			}
			else
				include("url_AccessDenied.php");
			break;
			
//Pages

 		case "pages":
 			if($session->check())
 			{
				$session->update();
				if($acl->allowed("pages"))
				{
					include("../lib/lib_MPTT.php");
					$mptt=new MPTT($db,"cms_pages",$session->getValue("siteid"));
					include("qry_Trail.php");
					include("qry_Pages.php");
					include("dsp_Pages.php");
				}
 			}
 			else
 				include("url_AccessDenied.php");
 			break;
//Order
		case "orderPage":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("orderPage"))
				{
					include("../lib/lib_MPTT.php");
					$mptt=new MPTT($db,"cms_pages",$session->getValue("siteid"));
					if($_REQUEST['dir']=="up")
						include("act_MovePageUp.php");
					else
						include("act_MovePageDown.php");
					if($ok) header("location: ".$config['dir']."index.php?fuseaction=admin.pages&parent_id=".$_REQUEST['parent_id']);
				}
			}
			else
				include("url_AccessDenied.php");
			break;
//Move
		case "movePage":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("movePage"))
				{
					if($_REQUEST['act']=="")
					{
						include("qry_MovePage.php");
						include("dsp_MovePage.php");
					}
					else if($_REQUEST['act']=="move")
					{
						include("../lib/lib_MPTT.php");
						include("../lib/lib_HTAccess.php");
						$mptt=new MPTT($db,"cms_pages",$session->getValue("siteid"));
						include("act_MovePage.php");
						$session->setValue("moveToken",false);
						if($ok) header("location: ".$config['dir']."index.php?fuseaction=admin.pages&parent_id=".$_REQUEST['parent_id']);
					}
				}
			}
			else
				include("url_AccessDenied.php");
			break;
//Actions
 		case "addPage":
 			if($session->check())
 			{
				$session->update();
				if($acl->allowed("addPage"))
				{
					include("../lib/lib_Validation.php");
					include("val_AddPage.php");

					if($_REQUEST['act']=="")
					{
						include("qry_Layouts.php");
						include("dsp_AddPageSelectLayout.php");
					}
					else if($_REQUEST['act']=="data")
					{
						include("../lib/lib_WYSIWYG.php");
						include("qry_CurrentSite.php");
						include("qry_Layout.php");
						include("dsp_AddPage.php");
					}
					else
					{
						if($validator->validate($_POST))
						{
							include("../lib/lib_Search.php");
							include("../lib/lib_MPTT.php");
							$mptt=new MPTT($db,"cms_pages",$session->getValue("siteid"));
							include("act_AddPage.php");
							if($acl->check("instantAdd") && $_POST['instant']=="on")
							{
								include("../lib/lib_HTAccess.php");
								include("../VLib/lib_Sitemap.php");
								include("act_ApproveAdd.php");
							}
							if($ok) header("location: ".$config['dir']."index.php?fuseaction=admin.pages&parent_id=".$_REQUEST['parent_id']);
						}
						else
						{
							include("../lib/lib_WYSIWYG.php");
							include("qry_CurrentSite.php");
							include("qry_Layout.php");
							include("dsp_AddPage.php");
						}
					}
				}
 			}
 			else
 				include("url_AccessDenied.php");
 			break;

 		case "editPage":
 			if($session->check())
 			{
				$session->update();
				if($acl->allowed("editPage"))
				{
					include("../lib/lib_Validation.php");
					include("val_EditPage.php");

					include("../lib/lib_MPTT.php");
					$mptt=new MPTT($db,"cms_pages",$session->getValue("siteid"));

					if($_REQUEST['act']=="")
					{
						include("../lib/lib_WYSIWYG.php");;
						include("qry_Page.php");
						include("qry_Layout.php");
						include("dsp_EditPage.php");
					}
					else
					{
						if($validator->validate($_POST))
						{
							include("../lib/lib_Search.php");
							include("act_UpdatePage.php");
							if($acl->check("instantEdit") && $_POST['instant']=="on")
							{
								include("../lib/lib_HTAccess.php");
								include("../VLib/lib_Sitemap.php");
								include("act_ApproveEdit.php");
							}
							if($ok) header("location: ".$config['dir']."index.php?fuseaction=admin.pages&parent_id=".$_REQUEST['parent_id']);
						}
						else
						{
							include("../lib/lib_WYSIWYG.php");;
							include("qry_Page.php");
							include("qry_Layout.php");
							include("dsp_EditPage.php");
						}
					}
				}
 			}
 			else
 				include("url_AccessDenied.php");
 			break;

 		case "removePage":
 			if($session->check())
 			{
				$session->update();
				if($acl->allowed("removePage"))
				{
					include("act_RemovePage.php");
					if($db->ErrorNo()==0) header("location: ".$config['dir']."index.php?fuseaction=admin.pages&parent_id=".$_REQUEST['parent_id']);
				}
 			}
 			else
 				include("url_AccessDenied.php");
 			break;

 		case "pageListings":
 			if($session->check())
 			{
				$session->update();
				if($acl->allowed("pageListings"))
				{
					if($_REQUEST['act']=="")
					{
						include("qry_PageListings.php");
						include("dsp_PageListings.php");
					}
					else if($_REQUEST['act']=="removeListing")
					{
						include("act_RemovePageListing.php");
						include("qry_PageListings.php");
						include("dsp_PageListings.php");
					}
					else if($_REQUEST['act']=="addListing")
					{
						include("dsp_AddListing.php");
					}
					else if($_REQUEST['act']=="editListing")
					{
						include("qry_PageListing.php");
						include("dsp_EditListing.php");
					}
					else if($_REQUEST['act']=="save")
					{
						include("act_UpdatePageListing.php");
						include("qry_PageListings.php");
						include("dsp_PageListings.php");
					}
					else
					{
						include("act_AddPageListing.php");
						include("qry_PageListings.php");
						include("dsp_PageListings.php");
					}
				}
 			}
 			else
 				include("url_AccessDenied.php");
 			break;

//Editorial
 		case "approveAdd":
 			if($session->check())
 			{
				$session->update();
				if($acl->allowed("approveAdd"))
				{
					if($_REQUEST['act']=="")
					{
						include("qry_Page.php");
						include("qry_Layout.php");
						include("dsp_Addition.php");
					}
					else if($_REQUEST['act']=="approve")
					{
						include("../lib/lib_MPTT.php");
						$mptt=new MPTT($db,"cms_pages",$session->getValue("siteid"));
						include("../lib/lib_HTAccess.php");
						include("../VLib/lib_Sitemap.php");
						$pageid=$_POST['pageid'];
						include("act_ApproveAdd.php");
						if($ok) header("location: ".$config['dir']."index.php?fuseaction=admin.pages&parent_id=".$_REQUEST['parent_id']);
					}
					else if($_REQUEST['act']=="reject")
					{
						include("../lib/lib_MPTT.php");
						$mptt=new MPTT($db,"cms_pages",$session->getValue("siteid"));
						include("act_RejectAdd.php");
						if($ok) header("location: ".$config['dir']."index.php?fuseaction=admin.pages&parent_id=".$_REQUEST['parent_id']);
					}
				}
 			}
 			else
 				include("url_AccessDenied.php");
 			break;

 		case "approveEdit":
 			if($session->check())
 			{
				$session->update();
				if($acl->allowed("approveEdit"))
				{
					if($_REQUEST['act']=="")
					{
						include("qry_Page.php");
						include("qry_Edits.php");
						include("dsp_Edits.php");
					}
					else if($_REQUEST['act']=="compare")
					{
						include("../lib/lib_Diff.php");
						include("qry_Page.php");
						include("qry_Edit.php");
						include("dsp_Edit.php");
					}
					else if($_REQUEST['act']=="approve")
					{
						include("../lib/lib_HTAccess.php");
						include("../VLib/lib_Sitemap.php");
						$revision=$_REQUEST['revision'];
						include("act_ApproveEdit.php");
						if($ok) header("location: ".$config['dir']."index.php?fuseaction=admin.pages&parent_id=".$_REQUEST['parent_id']);
					}
					else if($_REQUEST['act']=="reject")
					{
						include("act_RejectEdit.php");
						if($ok) header("location: ".$config['dir']."index.php?fuseaction=admin.pages&parent_id=".$_REQUEST['parent_id']);
					}
				}
 			}
 			else
 				include("url_AccessDenied.php");
 			break;

 		case "approveRemove":
 			if($session->check())
 			{
				$session->update();
				if($acl->allowed("approveRemove"))
				{
					if($_REQUEST['act']=="")
					{
						include("qry_Page.php");
						include("dsp_Removal.php");
					}
					else if($_REQUEST['act']=="approve")
					{
						include("../lib/lib_MPTT.php");
						$mptt=new MPTT($db,"cms_pages",$session->getValue("siteid"));
						include("../lib/lib_HTAccess.php");
						include("../VLib/lib_Sitemap.php");
						include("act_ApproveRemove.php");
						if($ok) header("location: ".$config['dir']."index.php?fuseaction=admin.pages&parent_id=".$_REQUEST['parent_id']);
					}
					else if($_REQUEST['act']=="reject")
					{
						include("act_RejectRemove.php");
						if($ok) header("location: ".$config['dir']."index.php?fuseaction=admin.pages&parent_id=".$_REQUEST['parent_id']);
					}
				}
 			}
 			else
 				include("url_AccessDenied.php");
 			break;

 		case "rollback":
 			if($session->check())
 			{
				$session->update();
				if($acl->allowed("rollback"))
				{
					if($_REQUEST['act']=="")
					{
						include("qry_Rollbacks.php");
						include("dsp_Rollbacks.php");
					}
					else if($_REQUEST['act']=="compare")
					{
						include("../lib/lib_Diff.php");
						include("qry_Page.php");
						include("qry_Rollback.php");
						include("dsp_Rollback.php");
					}
					else if($_REQUEST['act']=="rollback")
					{
						include("../lib/lib_HTAccess.php");
						include("act_Rollback.php");
						if($ok) header("location: ".$config['dir']."index.php?fuseaction=admin.pages&parent_id=".$_REQUEST['parent_id']);
					}
				}
 			}
 			else
 				include("url_AccessDenied.php");
 			break;

 		case "pendingEdits":
 			if($session->check())
 			{
	 			$session->update();
	 			if($acl->allowed("pendingEdits"))
	 			{
		 			if($_REQUEST['act']=="")
		 			{
			 			include("qry_PendingEdits.php");
			 			include("dsp_PendingEdits.php");
		 			}
	 			}
 			}
 			else
 				include("url_AccessDenied.php");
 			break;

 		case "pendingAdditions":
 			if($session->check())
 			{
	 			$session->update();
	 			if($acl->allowed("pendingAdditions"))
	 			{
		 			if($_REQUEST['act']=="")
		 			{
			 			include("qry_PendingAdditions.php");
			 			include("dsp_PendingAdditions.php");
		 			}
	 			}
 			}
 			else
 				include("url_AccessDenied.php");
 			break;

 		case "pendingRemovals":
 			if($session->check())
 			{
	 			$session->update();
	 			if($acl->allowed("pendingRemovals"))
	 			{
		 			if($_REQUEST['act']=="")
		 			{
			 			include("qry_PendingRemovals.php");
			 			include("dsp_PendingRemovals.php");
		 			}
	 			}
 			}
 			else
 				include("url_AccessDenied.php");
 			break;


//Items

// PageImages
		case "pageImages":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("pageImages"))
				{
					include("qry_PageImages.php");
					include("dsp_PageImages.php");
				}
			}
			else
				include("url_AccessDenied.php");
			break;
			
		case "addPageImage":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("addPageImage"))
				{
					if($_REQUEST['act']=="")
					{
						include("dsp_AddPageImage.php");
					}
					else
					{
						include("../VLib/lib_VImage.php");
						include("../VLib/VImage/cls_".$vcfg['vimage']['cls'].".php");
					
						include("act_AddPageImage.php");
						if($ok) { header("location: ".$config['dir']."index.php?fuseaction=admin.pageImages&pageid=".$_REQUEST['pageid']."&parent_id=".$_REQUEST['parent_id']); exit; }
					}
				}
			}
			else
				include("url_AccessDenied.php");
			break;

        case "editPageImage":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("addPageImage"))
				{
					if($_REQUEST['act']=="")
					{
                        include("qry_PageImage.php");
						include("dsp_EditPageImage.php");
					}
					else
					{
						include("../VLib/lib_VImage.php");
						include("../VLib/VImage/cls_".$vcfg['vimage']['cls'].".php");

                        include("qry_PageImage.php");
						include("act_UpdatePageImage.php");
						if($ok) { header("location: ".$config['dir']."index.php?fuseaction=admin.pageImages&pageid=".$_REQUEST['pageid']."&parent_id=".$_REQUEST['parent_id']); exit; }
					}
				}
			}
			else
				include("url_AccessDenied.php");
			break;
			
		case "removePageImage":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("removePageImage"))
				{
					include("qry_PageImage.php");
					include("act_RemovePageImage.php");
					if($ok) { header("location: ".$config['dir']."index.php?fuseaction=admin.pageImages&pageid=".$_REQUEST['pageid']."&parent_id=".$_REQUEST['parent_id']); exit; }
				}
			}
			else
				include("url_AccessDenied.php");
			break;
			
		case "orderPageImage":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("orderPageImage"))
				{
					if($_REQUEST['dir']=="up")
						include("act_MovePageImageUp.php");
					else
						include("act_MovePageImageDown.php");
					header("location: ".$config['dir']."index.php?fuseaction=admin.pageImages&pageid=".$_REQUEST['pageid']."&parent_id=".$_REQUEST['parent_id']);
				}
			}
			else
				include("url_AccessDenied.php");
			break;
			

 		case "pageLayout":
 			if($session->check())
 			{
				$session->update();
				if($acl->allowed("pageLayout"))
				{
					if($_REQUEST['act']=="")
					{
						include("qry_Page.php");
						include("qry_Layouts.php");
						include("dsp_PageLayout.php");
					}
					else if($_REQUEST['act']=="change")
					{
						include("act_PageLayout.php");
						if($ok)
						{
							if($acl->check("instantChangeLayout") && $_POST['instant']=="on")
							{
								include("../lib/lib_HTAccess.php");
								include("../VLib/lib_Sitemap.php");
								include("act_ApproveEdit.php");
							}
							if($ok) header("location: ".$config['dir']."index.php?fuseaction=admin.pages&parent_id=".$_REQUEST['parent_id']);
						}
					}
				}
 			}
 			else
 				include("url_AccessDenied.php");
 			break;
			
		case "deleted":
 			if($session->check())
 			{
	 			$session->update();
	 			if($acl->allowed("deleted"))
	 			{
		 			include("qry_Deleted.php");
		 			include("dsp_Deleted.php");
	 			}
 			}
 			else
 				include("url_AccessDenied.php");
 			break;

 		case "unDelete":
 			if($session->check())
 			{
	 			$session->update();
	 			if($acl->allowed("unDelete"))
	 			{
					include("../lib/lib_MPTT.php");
					$mptt=new MPTT($db,"cms_pages",$session->getValue("siteid"));
					include("../lib/lib_HTAccess.php");
		 			include("act_Undelete.php");
		 			if($ok) header("location: ".$config['dir']."index.php?fuseaction=admin.pages&parent_id=".$_REQUEST['parent_id']);
	 			}
 			}
 			else
 				include("url_AccessDenied.php");
 			break;

    //Press

 		case "press":
 			if($session->check())
 			{
	 			$session->update();
	 			if($acl->allowed("press"))
	 			{
					include('qry_Press.php');
					include('dsp_Press.php');
	 			}
 			}
 			else
 				include("url_AccessDenied.php");
 			break;

 		case "editPress":
 			if($session->check())
 			{
	 			$session->update();
	 			if($acl->allowed($_GET['press_id'] ? 'editPress' : 'addPress'))
	 			{
                    include('qry_Press.php');
                    include("../lib/lib_WYSIWYG.php");

                    if(strtolower($_SERVER['REQUEST_METHOD']) == 'post'){
                        include('act_SavePress.php');
                    }

                    include('dsp_EditPress.php');
	 			}
 			}
 			else
 				include("url_AccessDenied.php");
 			break;

 		case "deletePressImage":
 			if($session->check())
 			{
	 			$session->update();
	 			if($acl->allowed('editPress'))
	 			{
                    include('qry_Press.php');
                    include('act_DeletePressImage.php');
	 			}
 			}
 			else
 				include("url_AccessDenied.php");
 			break;

 		case "deletePress":
 			if($session->check())
 			{
	 			$session->update();
	 			if($acl->allowed('deletePress'))
	 			{
                    include('act_DeletePress.php');
	 			}
 			}
 			else
 				include("url_AccessDenied.php");
 			break;
 
/********************************************************************************
 * Configuration
 *
 *
 ********************************************************************************/

//Change Password
		case "password":
			if($session->check())
			{
				$session->update();
				if($_REQUEST['act']=="")
				{
					include("dsp_Password.php");
				}
				else
				{
					include("qry_ChangePassword.php");
					if($match)
					{
						if($_POST['newpassword']==$_POST['confirmpassword'])
						{
							include("act_UpdatePassword.php");
							include("dsp_PasswordChanged.php");
						}
						else
						{
							$reason="Your new password and confirmation do not match";
							include("dsp_Password.php");
						}
					}
					else
					{
						$reason="You entered your current password incorrectly";
						include("dsp_Password.php");
					}
				}
			}
			else
				include("url_AccessDenied.php");
			break;

//Setup
		case "setup":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("setup"))
				{
					if($_REQUEST['act']=="")
					{
						include("qry_Countries.php");
						include("qry_Templates.php");
						include("dsp_Setup.php");
					}
					else
					{
						include("act_UpdateSetup.php");
						include("dsp_SetupSaved.php");
					}
				}
			}
			else
				include("url_AccessDenied.php");
			break;

// 404 content
		case "page404":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("page404"))
				{
					if($_REQUEST['act']=="")
					{
						include("../lib/lib_WYSIWYG.php");
						
						include("qry_Page404.php");
						include("dsp_Page404.php");
					}
					else
					{
						include("act_UpdatePage404.php");
						if($ok) header("location: ".$config['dir']."index.php?fuseaction=admin.page404");
					}
				}
			}
			else
				include("url_AccessDenied.php");
			break;

/********************************************************************************
 * Accounts
 *
 *
 ********************************************************************************/

		case "accounts":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("accounts"))
				{
					include("qry_Accounts.php");
					include("dsp_Accounts.php");
				}
			}
			else
				include("url_AccessDenied.php");
			break;

		case "addAccount":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("addAccount"))
				{
					include("../lib/lib_Password.php");
					include("../lib/lib_Email.php");
					if($_REQUEST['act']=="")
					{
						include("qry_ACLGroups.php");
						include("dsp_AddAccount.php");
					}
					else
					{
						include("qry_CheckUsername.php");
						if($unique)
						{
							include("qry_CheckPassword.php");
							if($match)
							{
								include("act_AddAccount.php");
								if($ok) { header("location: ".$config['dir']."index.php?fuseaction=admin.accounts"); exit; }
							}
							else
							{
								$reason="Passwords entered do not match";
								include("qry_ACLGroups.php");
								include("dsp_AddAccount.php");
							}
						}
						else
						{
							$reason="Username is already in use";
							include("qry_ACLGroups.php");
							include("dsp_AddAccount.php");
						}
					}
				}
			}
			else
				include("url_AccessDenied.php");
			break;

		case "editAccount":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("editAccount"))
				{
					include("../lib/lib_Password.php");
					include("../lib/lib_Email.php");
					if($_REQUEST['act']=="")
					{
						include("qry_Account.php");
						include("qry_ACLGroups.php");
						include("dsp_EditAccount.php");
					}
					else
					{
						include("qry_CheckPassword.php");
						if($match)
						{
							include("act_UpdateAccount.php");
							if($ok) { header("location: ".$config['dir']."index.php?fuseaction=admin.accounts"); exit; }
						}
						else
						{
							$reason="Passwords entered do not match";
							include("qry_ACLGroups.php");
							include("qry_Account.php");
							include("dsp_EditAccount.php");
						}
					}
				}
			}
			else
				include("url_AccessDenied.php");
			break;

		case "removeAccount":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("removeAccount"))
				{
					include("act_RemoveAccount.php");
					if($ok) { header("location: ".$config['dir']."index.php?fuseaction=admin.accounts"); exit; }
				}
			}
			else
				include("url_AccessDenied.php");
			break;

		case "ACLGroups":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("ACLGroups"))
				{
					include("qry_ACLGroups.php");
					include("dsp_ACLGroups.php");
				}
			}
			else
				include("url_AccessDenied.php");
			break;
			
		case "addACLGroup":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("addACLGroup"))
				{
					if($_REQUEST['act']=="")
					{
						include("qry_ACLGroup.php");
						include("dsp_AddACLGroup.php");
					}
					else
					{
						include("act_AddACLGroup.php");
						if($ok) { header("location: ".$config['dir']."index.php?fuseaction=admin.ACLGroups"); exit; }
					}
				}
			}
			else
				include("url_AccessDenied.php");
			break;

		case "removeACLGroup":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("removeACLGroup"))
				{
					include("act_RemoveACLGroup.php");
					if($ok) { header("location: ".$config['dir']."index.php?fuseaction=admin.ACLGroups"); exit; }
				}
			}
			else
				include("url_AccessDenied.php");
			break;

		case "editACLGroup":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("editACLGroup"))
				{
					if($_REQUEST['act']=="")
					{
						include("qry_ACLGroup.php");
						include("dsp_EditACLGroup.php");
					}
					else
					{
						include("act_UpdateACLGroup.php");
						if($ok) { header("location: ".$config['dir']."index.php?fuseaction=admin.ACLGroups"); exit; }
					}
				}
			}
			else
				include("url_AccessDenied.php");
			break;

		case "sessions":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("sessions"))
				{
					include("qry_Sessions.php");
					include("dsp_Sessions.php");
				}
			}
			else
				include("url_AccessDenied.php");
			break;

		case "endSession":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("endSession"))
				{
					include("act_RemoveSession.php");
					if($ok) { header("location: ".$config['dir']."index.php?fuseaction=admin.sessions"); exit; }
				}
			}
			else
				include("url_AccessDenied.php");
			break;
			
// Meta Tags
		case "meta_tags":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("meta_tags"))
				{
					include("qry_MetaTags.php");
					include("dsp_MetaTags.php");
				}
			}
			else
				include("url_AccessDenied.php");
			break;
			
		case "addMetaTag":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("addMetaTag"))
				{
					include("../lib/lib_Validation.php");
					include("val_EditMetaTag.php");
					
					if($_REQUEST['act']=="")
					{
						include("dsp_AddMetaTag.php");
					}
					else
					{
						if($validator->validate($_POST))
						{
							include("act_AddMetaTag.php");
							if($ok) { header("location: ".$config['dir']."index.php?fuseaction=admin.meta_tags"); exit; }
						}
						else
						{
							include("dsp_AddMetaTag.php");
						}
					}
				}
			}
			else
				include("url_AccessDenied.php");
			break;
			
		case "editMetaTag":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("editMetaTag"))
				{
					include("../lib/lib_Validation.php");
					include("val_EditMetaTag.php");
					
					if($_REQUEST['act']=="")
					{
						include("qry_MetaTag.php");
						include("dsp_EditMetaTag.php");
					}
					else
					{
						if($validator->validate($_POST))
						{
							include("act_UpdateMetaTag.php");
							if($ok) { header("location: ".$config['dir']."index.php?fuseaction=admin.meta_tags"); exit; }
						}
						else
						{
							include("qry_MetaTag.php");
							include("dsp_EditMetaTag.php");
						}
					}
				}
			}
			else
				include("url_AccessDenied.php");
			break;
			
		case "removeMetaTag":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("removeMetaTag"))
				{
					include("act_RemoveMetaTag.php");
					if($ok) { header("location: ".$config['dir']."index.php?fuseaction=admin.meta_tags"); exit; }
				}
			}
			else
				include("url_AccessDenied.php");
			break;

// Product Reviews
		case "productReviews":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("productReviews"))
				{
					include("qry_ProductReviews.php");
					include("dsp_ProductReviews.php");
				}
			}
			else
				include("url_AccessDenied.php");
			break;
			
		case "addProductReview":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("addProductReview"))
				{
					include("../lib/lib_Validation.php");
					include("val_EditProductReview.php");
					
					if($_REQUEST['act']=="")
					{
						include("dsp_AddProductReview.php");
					}
					else
					{
						if($validator->validate($_POST))
						{
							include("act_AddProductReview.php");
							if($ok)
							{ 
								switch($_REQUEST['return'])
								{
									case 'reviews':
										$url = $config['dir']."index.php?fuseaction=admin.reviews";
										break;
									case 'rejected':
										$url = $config['dir']."index.php?fuseaction=admin.rejectedReviews";
										break;
									default:
										$url = $config['dir']."index.php?fuseaction=admin.productReviews&product_id=".$_REQUEST['product_id']."&category_id=".$_REQUEST['category_id'];
										break;
								}
								header("location: ".$url); 
								exit; 
							}
						}
						else
						{
							include("dsp_AddProductReview.php");
						}
					}
				}
			}
			else
				include("url_AccessDenied.php");
			break;
			
		case "editProductReview":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("editProductReview"))
				{
					include("../lib/lib_Validation.php");
					include("val_EditProductReview.php");
					
					if($_REQUEST['act']=="")
					{
						include("qry_ProductReview.php");
						include("dsp_EditProductReview.php");
					}
					else
					{
						if($validator->validate($_POST))
						{
							include("act_UpdateProductReview.php");
							if($ok)
							{ 
								switch($_REQUEST['return'])
								{
									case 'reviews':
										$url = $config['dir']."index.php?fuseaction=admin.reviews";
										break;
									case 'rejected':
										$url = $config['dir']."index.php?fuseaction=admin.rejectedReviews";
										break;
									case 'approved':
										$url = $config['dir']."index.php?fuseaction=admin.approvedReviews";
										break;
									default:
										$url = $config['dir']."index.php?fuseaction=admin.productReviews&product_id=".$_REQUEST['product_id']."&category_id=".$_REQUEST['category_id'];
										break;
								}
								header("location: ".$url); 
								exit; 
							}
						}
						else
						{
							include("qry_ProductReview.php");
							include("dsp_EditProductReview.php");
						}
					}
				}
			}
			else
				include("url_AccessDenied.php");
			break;
			
		case "removeProductReview":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("removeProductReview"))
				{
					include("act_RemoveProductReview.php");
					if($ok)
					{ 
						switch($_REQUEST['return'])
						{
							case 'reviews':
								$url = $config['dir']."index.php?fuseaction=admin.reviews";
								break;
							case 'rejected':
								$url = $config['dir']."index.php?fuseaction=admin.rejectedReviews";
								break;
							case 'approved':
								$url = $config['dir']."index.php?fuseaction=admin.approvedReviews";
								break;
							default:
								$url = $config['dir']."index.php?fuseaction=admin.productReviews&product_id=".$_REQUEST['product_id']."&category_id=".$_REQUEST['category_id'];
								break;
						}
						header("location: ".$url); 
						exit; 
					}
				}
			}
			else
				include("url_AccessDenied.php");
			break;
			
		case "approveProductReview":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("approveProductReview"))
				{
					include("act_ApproveProductReview.php");
					if($ok)
					{ 
						switch($_REQUEST['return'])
						{
							case 'reviews':
								$url = $config['dir']."index.php?fuseaction=admin.reviews";
								break;
							case 'rejected':
								$url = $config['dir']."index.php?fuseaction=admin.rejectedReviews";
								break;
							default:
								$url = $config['dir']."index.php?fuseaction=admin.productReviews&product_id=".$_REQUEST['product_id']."&category_id=".$_REQUEST['category_id'];
								break;
						}
						header("location: ".$url); 
						exit; 
					}
				}
			}
			else
				include("url_AccessDenied.php");
			break;
			
		case "rejectProductReview":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("rejectProductReview"))
				{
					include("act_RejectProductReview.php");
					if($ok)
					{ 
						switch($_REQUEST['return'])
						{
							case 'reviews':
								$url = $config['dir']."index.php?fuseaction=admin.reviews";
								break;
							case 'rejected':
								$url = $config['dir']."index.php?fuseaction=admin.rejectedReviews";
								break;
							default:
								$url = $config['dir']."index.php?fuseaction=admin.productReviews&product_id=".$_REQUEST['product_id']."&category_id=".$_REQUEST['category_id'];
								break;
						}
						header("location: ".$url); 
						exit; 
					}
				}
			}
			else
				include("url_AccessDenied.php");
			break;
			
		case "reviews":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("reviews"))
				{
					include("qry_Reviews.php");
					include("dsp_Reviews.php");
				}
			}
			else
				include("url_AccessDenied.php");
			break;
			
		case "rejectedReviews":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("rejectedReviews"))
				{
					include("qry_RejectedReviews.php");
					include("dsp_RejectedReviews.php");
				}
			}
			else
				include("url_AccessDenied.php");
			break;
			
		case "approvedReviews":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("approvedReviews"))
				{
					include("qry_ApprovedReviews.php");
					include("dsp_ApprovedReviews.php");
				}
			}
			else
				include("url_AccessDenied.php");
			break;
			
// Settings
		case "settings":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("settings"))
				{
					if($_REQUEST['act']=="")
					{
						include("qry_GoogleCategories.php");
						include("qry_Settings.php");
						include("dsp_Settings.php");
					}
					else
					{
						include("act_UpdateSettings.php");
						if($ok) { header("location: ".$config['dir']."index.php?fuseaction=admin.settings"); exit; }
					}
				}
			}
			else
				include("url_AccessDenied.php");
			break;
			
// Cart
		case "cart":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("cart"))
				{
					if($_REQUEST['act']=="")
					{
						include("qry_Cart.php");
						include("dsp_Cart.php");
					}
					else
					{
						include("act_UpdateCart.php");
						if($ok) { header("location: ".$config['dir']."index.php?fuseaction=admin.cart"); exit; }
					}
				}
			}
			else
				include("url_AccessDenied.php");
			break;
			
// Invoice
		case "invoice":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("invoice"))
				{
					if($_REQUEST['act']=="")
					{
						include("qry_Invoice.php");
						include("dsp_Invoice.php");
					}
					else
					{
						include("act_UpdateInvoice.php");
						if($ok) { header("location: ".$config['dir']."index.php?fuseaction=admin.invoice"); exit; }
					}
				}
			}
			else
				include("url_AccessDenied.php");
			break;
			
// Similar Products
		case "similarProducts":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("similarProducts"))
				{
					include("qry_AllProducts.php");
					include("qry_SimilarProducts.php");
					include("dsp_SimilarProducts.php");
				}
			}
			else
				include("url_AccessDenied.php");
			break;
			
		case "addSimilarProduct":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("addSimilarProduct"))
				{
					if($_REQUEST['act']=="")
					{
						include("qry_AddSimilarProduct.php");
						include("dsp_AddSimilarProduct.php");
					}
					else
					{
						include("act_AddSimilarProduct.php");
						if($ok) { header("location: ".$config['dir']."index.php?fuseaction=admin.similarProducts&product_id=".$_REQUEST['product_id']."&category_id=".$_REQUEST['category_id']); exit; }
					}
				}
			}
			else
				include("url_AccessDenied.php");
			break;
			
		case "removeSimilarProduct":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("removeSimilarProduct"))
				{
					include("act_RemoveSimilarProduct.php");
					if($ok) { header("location: ".$config['dir']."index.php?fuseaction=admin.similarProducts&product_id=".$_REQUEST['product_id']."&category_id=".$_REQUEST['category_id']); exit; }
				}
			}
			else
				include("url_AccessDenied.php");
			break;
			
// Discount Codes
		case "discountCodes":
			if($session->check())
 			{
				$session->update();
				if($acl->allowed("discountCodes"))
				{
					include("qry_DiscountCodes.php");
					include("dsp_DiscountCodes.php");
				}
			}
 			else
 				include("url_AccessDenied.php");
		break;
		
		case "addDiscountCode":
 			if($session->check())
 			{
				$session->update();
				if($acl->allowed("addDiscountCode"))
				{
					if($_REQUEST['act']=="")
					{
						include("dsp_AddDiscountCode.php");
					}
					else
					{
						include("act_AddDiscountCode.php");
						if($ok) header("location: ".$config['dir']."index.php?fuseaction=admin.discountCodes");
					}
				}
 			}
 			else
 				include("url_AccessDenied.php");
 			break;
			
		case "addCustomDiscountCode":
 			if($session->check())
 			{
				$session->update();
				if($acl->allowed("addDiscountCode"))
				{
					if($_REQUEST['act']=="")
					{
						include("qry_DiscountGiftRegistry.php");
						include("dsp_AddCustomDiscountCode.php");
					}
					else
					{
						include("act_AddCustomDiscountCode.php");
						if($ok) header("location: ".$config['dir']."index.php?fuseaction=admin.discountCodes");
					}
				}
 			}
 			else
 				include("url_AccessDenied.php");
 			break;
			
		case "removeDiscountCode": 
 			if($session->check())
 			{
				$session->update();
				if($acl->allowed("removeDiscountCode"))
				{
					include("act_RemoveDiscountCode.php");
					if($ok) header("location: ".$config['dir']."index.php?fuseaction=admin.discountCodes");
				}
 			}
 			else
 				include("url_AccessDenied.php");
 			break;
			
		case "suspendDiscountCode": 
 			if($session->check())
 			{
				$session->update();
				if($acl->allowed("removeDiscountCode"))
				{
					include("act_SuspendDiscountCode.php");
					if($ok) header("location: ".$config['dir']."index.php?fuseaction=admin.discountCodes");
				}
 			}
 			else
 				include("url_AccessDenied.php");
 			break;
			
		case "assignDiscountCode":
 			if($session->check())
 			{
				$session->update();
				if($acl->allowed("assignDiscountCode"))
				{
					if($_REQUEST['act']=="")
					{
						include("qry_AssignDiscountCode.php");
						include("dsp_AssignDiscountCode.php");
					}
					else
					{
						include("act_AssignDiscountCode.php");
						if($ok) header("location: ".$config['dir']."index.php?fuseaction=admin.discountCodes");
					}
				}
 			}
 			else
 				include("url_AccessDenied.php");
 			break;
			
		case "assignCommissionDiscountCode":
 			if($session->check())
 			{
				$session->update();
				if($acl->allowed("assignDiscountCode"))
				{
					if($_REQUEST['act']=="")
					{
						include("qry_AssignCommissionDiscountCode.php");
						include("dsp_AssignCommissionDiscountCode.php");
					}
					else
					{
						include("act_AssignCommissionDiscountCode.php");
						if($ok) header("location: ".$config['dir']."index.php?fuseaction=admin.discountCodes");
					}
				}
 			}
 			else
 				include("url_AccessDenied.php");
 			break;
			
		case "editDiscountCode":
 			if($session->check())
 			{
				$session->update();
				if($acl->allowed("editDiscountCode"))
				{
					if($_REQUEST['act']=="")
					{
						include("qry_AssignDiscountCode.php");
						include("qry_AssignCommissionDiscountCode.php");
						include("qry_DiscountCode.php");
						include("qry_DiscountGiftRegistry.php");
						include("dsp_EditDiscountCode.php");
					}
					else
					{
						include("act_UpdateDiscountCode.php");
						if($ok)
						{
							include("qry_DiscountCode.php");
							if($discount_code['all_users'])
								include("act_AssignCommissionDiscountCode.php");
								
							if($ok) header("location: ".$config['dir']."index.php?fuseaction=admin.discountCodes");
						}
					}
				}
 			}
 			else
 				include("url_AccessDenied.php");
 			break;
			
//Emails
		case "emails":
 			if($session->check())
 			{
				$session->update();
				if($acl->allowed("emails"))
				{
					include("qry_Emails.php");
					include("dsp_Emails.php");
				}
 			}
 			else
 				include("url_AccessDenied.php");
 			break;

		case "addEmail":
 			if($session->check())
 			{
				$session->update();
				if($acl->allowed("addEmail"))
				{
					include("../lib/lib_Validation.php");
					include("val_AddEmail.php");

					if($_REQUEST['act']=="")
					{
						include("../lib/lib_WYSIWYG.php");
						include("dsp_AddEmail.php");
					}
					else
					{
						if($validator->validate($_POST))
						{
							include("act_AddEmail.php");
							if($ok) header("location: ".$config['dir']."index.php?fuseaction=admin.emails");
						}
						else
						{
							include("../lib/lib_WYSIWYG.php");
							include("dsp_AddEmail.php");
						}
					}
				}
 			}
 			else
 				include("url_AccessDenied.php");
 			break;

		case "editEmail":
 			if($session->check())
 			{
				$session->update();
				if($acl->allowed("editEmail"))
				{
					include("../lib/lib_Validation.php");
					include("val_AddEmail.php");

					if($_REQUEST['act']=="")
					{
						include("../lib/lib_WYSIWYG.php");
						include("qry_Email.php");
						include("dsp_EditEmail.php");
					}
					else
					{
						if($validator->validate($_POST))
						{
							include("act_UpdateEmail.php");
							if($ok) header("location: ".$config['dir']."index.php?fuseaction=admin.emails");
						}
						else
						{
							include("../lib/lib_WYSIWYG.php");
							include("qry_Email.php");
							include("dsp_EditEmail.php");
						}
					}
				}
 			}
 			else
 				include("url_AccessDenied.php");
 			break;

		case "removeEmail":
 			if($session->check())
 			{
				$session->update();
				if($acl->allowed("removeEmail"))
				{
					include("act_RemoveEmail.php");
					if($ok) header("location: ".$config['dir']."index.php?fuseaction=admin.emails");
				}
 			}
 			else
 				include("url_AccessDenied.php");
 			break;
			
// Newsletter Emails
		case "newsletterEmails":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("newsletterEmails"))
				{
					include("qry_NewsletterEmails.php");
					include("dsp_NewsletterEmails.php");
				}
			}
			else
				include("url_AccessDenied.php");
			break;
			
		case "removeNewsletterEmail":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("removeNewsletterEmail"))
				{
					include("act_RemoveNewsletterEmail.php");
					if($ok) { header("location: ".$config['dir']."index.php?fuseaction=admin.newsletterEmails"); exit; }
				}
			}
			else
				include("url_AccessDenied.php");
			break;
			
// Home Banner(s)
		case "homeBanner":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("homeBanner"))
				{
					if($_REQUEST['act']=="")
					{
						include("qry_HomeBanner.php");
						include("dsp_HomeBanner.php");
					}
					else
					{
						include("act_HomeBanner.php");
						if($ok) { header("location: ".$config['dir']."index.php?fuseaction=admin.homeBanner"); exit; }
					}
				}
			}
			else
				include("url_AccessDenied.php");
			break;
			
		case "homeBanners":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("homeBanners"))
				{
					include("qry_HomeBanners.php");
					include("dsp_HomeBanners.php");
				}
			}
			else
				include("url_AccessDenied.php");
			break;
			
		case "addHomeBanner":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("addHomeBanner"))
				{
					if($_REQUEST['act']=="")
					{
						include("dsp_AddHomeBanner.php");
					}
					else
					{
						include("act_AddHomeBanner.php");
						if($ok) { header("location: ".$config['dir']."index.php?fuseaction=admin.homeBanners"); exit; }
					}
				}
			}
			else
				include("url_AccessDenied.php");
			break;
			
		case "editHomeBanner":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("editHomeBanner"))
				{
					if($_REQUEST['act']=="")
					{
						include("qry_EditHomeBanner.php");
						include("dsp_EditHomeBanner.php");
					}
					else
					{
						include("qry_EditHomeBanner.php");
						include("act_UpdateHomeBanner.php");
						if($ok) { header("location: ".$config['dir']."index.php?fuseaction=admin.homeBanners"); exit; }
					}
				}
			}
			else
				include("url_AccessDenied.php");
			break;
			
		case "removeHomeBanner":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("removeHomeBanner"))
				{
					include("qry_EditHomeBanner.php");
					include("act_RemoveHomeBanner.php");
					if($ok) { header("location: ".$config['dir']."index.php?fuseaction=admin.homeBanners"); exit; }
				}
			}
			else
				include("url_AccessDenied.php");
			break;
			
		case "orderHomeBanner":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("orderHomeBanner"))
				{
					if($_REQUEST['dir']=="up")
						include("act_MoveHomeBannerUp.php");
					else
						include("act_MoveHomeBannerDown.php");
					header("location: ".$config['dir']."index.php?fuseaction=admin.homeBanners");
				}
			}
			else
				include("url_AccessDenied.php");
			break;
			
// Users
		case "users":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("users"))
				{
                    if($_REQUEST['act']=="")
					{
						include("qry_Users.php");
					    include("dsp_Users.php");
					}
					elseif($_REQUEST['act']=="newsletter_export")
					{
						include("qry_UsersNewsletterExport.php");
						include("dsp_UsersNewsletterExport.php");
					}
				}
			}
			else
				include("url_AccessDenied.php");
			break;
			
		case "addUser":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("addUser"))
				{
					include("../lib/lib_Validation.php");
					include("val_AddUser.php");
					
					if($_REQUEST['act']=="")
					{
						include("qry_AllCountries.php");
						include("dsp_AddUser.php");
					}
					else
					{
						if($validator->validate($_POST))
						{
							include("act_AddUser.php");
							
							// Create Authorize Profile Id
							if ( $user_id && $config['psp']['driver'] == 'Authorize' ) {
								include ("../lib/lib_Payment.php");
								include ("../lib/payment/cfg_Authorize.php");
								include ("../lib/payment/lib_Authorize.php");
								
								$psp = new Authorize($config,$smarty,$db);
								
								if ( $authorize_profile_id = $psp->CreateCustomerProfile($user_id,$_REQUEST['email'])) 
									include ("../users/act_UpdateAuthorizeProfileId.php");
								
							}
							
							if($ok) { header("location: ".$config['dir']."index.php?fuseaction=admin.users"); exit; }
						}
						else
						{
							include("qry_AllCountries.php");
							include("dsp_AddUser.php");
						}
					}
				}
			}
			else
				include("url_AccessDenied.php");
			break;
			
		case "editUser":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("editUser"))
				{
					include("../lib/lib_Validation.php");
					include("val_EditUser.php");
					
					if($_REQUEST['act']=="")
					{
						
						include("qry_AllCountries.php");
						include("qry_User.php");
						
						if($config['psp']['driver'] == 'Authorize') {
							include ("../lib/lib_Payment.php");
							include ("../lib/payment/cfg_Authorize.php");
							include ("../lib/payment/lib_Authorize.php");
									
							$psp = new Authorize($config,$smarty,$db);
							
							$token = $psp->getHostedProfilePage ( $user['authorize_profile_id'] );
							
						}
						
						include("dsp_EditUser.php");
						
							
								
					
					}
					else
					{
						if($validator->validate($_POST))
						{
							include("act_UpdateUser.php");
							if($ok) { header("location: ".$config['dir']."index.php?fuseaction=admin.users"); exit; }
						}
						else
						{
							include("qry_AllCountries.php");
							include("qry_User.php");
							include("dsp_EditUser.php");
						}
					}
				}
			}
			else
				include("url_AccessDenied.php");
			break;
			
		case "removeUser":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("removeUser"))
				{
					include ("qry_User.php");

					// Delete Customer Profile from Authorize.net
					if ( $user['authorize_profile_id']+0 != 0 && $config['psp']['driver'] == 'Authorize' ) {
						include ("../lib/lib_Payment.php");
						include ("../lib/payment/cfg_Authorize.php");
						include ("../lib/payment/lib_Authorize.php");
						$psp = new Authorize($config,$smarty,$db);
						
						$psp->DeleteCustomerProfile($user['authorize_profile_id']);
					}
					
					include("act_RemoveUser.php");
					if($ok) { header("location: ".$config['dir']."index.php?fuseaction=admin.users"); exit; }
				}
			}
			else
				include("url_AccessDenied.php");
			break;
			
		case "uploadStock":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("uploadStock"))
				{
					include("act_UploadStock.php");
				}
			}
			else
				include("url_AccessDenied.php");
			break;
			
// ContentAreas
		case "contentAreas":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("contentAreas"))
				{
					include("qry_ContentAreas.php");
					include("dsp_ContentAreas.php");
				}
			}
			else
				include("url_AccessDenied.php");
			break;
			
		case "addContentArea":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("addContentArea"))
				{
					include("../lib/lib_Validation.php");
					include("val_AddContentArea.php");
					
					if($_REQUEST['act']=="")
					{
						include("../lib/lib_WYSIWYG.php");
						include("dsp_AddContentArea.php");
					}
					else
					{
						if($validator->validate($_POST))
						{
							include("act_AddContentArea.php");
							if($ok) { header("location: ".$config['dir']."index.php?fuseaction=admin.contentAreas"); exit; }
						}
						else
						{
							include("../lib/lib_WYSIWYG.php");
							include("dsp_AddContentArea.php");
						}
					}
				}
			}
			else
				include("url_AccessDenied.php");
			break;
			
		case "editContentArea":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("editContentArea"))
				{
					include("../lib/lib_Validation.php");
					include("val_AddContentArea.php");
					
					if($_REQUEST['act']=="")
					{
						include("../lib/lib_WYSIWYG.php");
						include("qry_ContentArea.php");
						include("dsp_EditContentArea.php");
					}
					else
					{
						if($validator->validate($_POST))
						{
							include("act_UpdateContentArea.php");
							if($ok) { header("location: ".$config['dir']."index.php?fuseaction=admin.contentAreas"); exit; }
						}
						else
						{
							include("../lib/lib_WYSIWYG.php");
							include("qry_ContentArea.php");
							include("dsp_EditContentArea.php");
						}
					}
				}
			}
			else
				include("url_AccessDenied.php");
			break;
			
		case "removeContentArea":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("removeContentArea"))
				{
					include("act_RemoveContentArea.php");
					if($ok) { header("location: ".$config['dir']."index.php?fuseaction=admin.contentAreas"); exit; }
				}
			}
			else
				include("url_AccessDenied.php");
			break;
			
// Sizes
		case "sizes":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("sizes"))
				{
					include("qry_Sizes.php");
					include("dsp_Sizes.php");
				}
			}
			else
				include("url_AccessDenied.php");
			break;
			
		case "addSize":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("addSize"))
				{
					if($_REQUEST['act']=="")
					{
						include("dsp_AddSize.php");
					}
					else
					{
						include("act_AddSize.php");
						if($ok) { header("location: ".$config['dir']."index.php?fuseaction=admin.sizes"); exit; }
					}
				}
			}
			else
				include("url_AccessDenied.php");
			break;
			
		case "editSize":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("editSize"))
				{
					if($_REQUEST['act']=="")
					{
						include("qry_Size.php");
						include("dsp_EditSize.php");
					}
					else
					{
						include("act_UpdateSize.php");
						if($ok) { header("location: ".$config['dir']."index.php?fuseaction=admin.sizes"); exit; }
					}
				}
			}
			else
				include("url_AccessDenied.php");
			break;
			
		case "removeSize":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("removeSize"))
				{
					include("act_RemoveSize.php");
					if($ok) { header("location: ".$config['dir']."index.php?fuseaction=admin.sizes"); exit; }
				}
			}
			else
				include("url_AccessDenied.php");
			break;
			
		case "orderSize":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("orderSize"))
				{
					if($_REQUEST['dir']=="up")
						include("act_MoveSizeUp.php");
					else
						include("act_MoveSizeDown.php");
					header("location: ".$config['dir']."index.php?fuseaction=admin.sizes");
				}
			}
			else
				include("url_AccessDenied.php");
			break;
			
		case "sortSize":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("orderSize"))
				{
					include("act_SortSize.php");
					exit;
				}
			}
			else
				include("url_AccessDenied.php");
			break;
			
// Widths
		case "widths":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("widths"))
				{
					include("qry_Widths.php");
					include("dsp_Widths.php");
				}
			}
			else
				include("url_AccessDenied.php");
			break;
			
		case "addWidth":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("addWidth"))
				{
					include("../lib/lib_Validation.php");
					include("val_AddWidth.php");
					
					if($_REQUEST['act']=="")
					{
						include("dsp_AddWidth.php");
					}
					else
					{
						if($validator->validate($_POST))
						{
							include("act_AddWidth.php");
							if($ok) { header("location: ".$config['dir']."index.php?fuseaction=admin.widths"); exit; }
						}
						else
						{
							include("dsp_AddWidth.php");
						}
					}
				}
			}
			else
				include("url_AccessDenied.php");
			break;
			
		case "editWidth":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("editWidth"))
				{
					include("../lib/lib_Validation.php");
					include("val_EditWidth.php");
					
					if($_REQUEST['act']=="")
					{
						include("qry_Width.php");
						include("dsp_EditWidth.php");
					}
					else
					{
						if($validator->validate($_POST))
						{
							include("act_UpdateWidth.php");
							if($ok) { header("location: ".$config['dir']."index.php?fuseaction=admin.widths"); exit; }
						}
						else
						{
							include("qry_Width.php");
							include("dsp_EditWidth.php");
						}
					}
				}
			}
			else
				include("url_AccessDenied.php");
			break;
			
		case "removeWidth":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("removeWidth"))
				{
					include("act_RemoveWidth.php");
					if($ok) { header("location: ".$config['dir']."index.php?fuseaction=admin.widths"); exit; }
				}
			}
			else
				include("url_AccessDenied.php");
			break;
			
		case "orderWidth":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("orderWidth"))
				{
					if($_REQUEST['dir']=="up")
						include("act_MoveWidthUp.php");
					else
						include("act_MoveWidthDown.php");
					header("location: ".$config['dir']."index.php?fuseaction=admin.widths");
				}
			}
			else
				include("url_AccessDenied.php");
			break;
			
		case "sortWidth":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("orderWidth"))
				{
					include("act_SortWidth.php");
					exit;
				}
			}
			else
				include("url_AccessDenied.php");
			break;
			
// Colors
		case "colors":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("colors"))
				{
					include("qry_Colors.php");
					include("dsp_Colors.php");
				}
			}
			else
				include("url_AccessDenied.php");
			break;
			
		case "addColor":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("addColor"))
				{
					include("../lib/lib_Validation.php");
					include("val_AddColor.php");
					
					if($_REQUEST['act']=="")
					{
						include("dsp_AddColor.php");
					}
					else
					{
						if($validator->validate($_POST))
						{
							include("act_AddColor.php");
							if($ok) { header("location: ".$config['dir']."index.php?fuseaction=admin.colors"); exit; }
						}
						else
						{
							include("dsp_AddColor.php");
						}
					}
				}
			}
			else
				include("url_AccessDenied.php");
			break;
			
		case "editColor":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("editColor"))
				{
					include("../lib/lib_Validation.php");
					include("val_EditColor.php");
					
					if($_REQUEST['act']=="")
					{
						include("qry_Color.php");
						include("dsp_EditColor.php");
					}
					else
					{
						if($validator->validate($_POST))
						{
							include("qry_Color.php");
							include("act_UpdateColor.php");
							if($ok) { header("location: ".$config['dir']."index.php?fuseaction=admin.colors"); exit; }
						}
						else
						{
							include("qry_Color.php");
							include("dsp_EditColor.php");
						}
					}
				}
			}
			else
				include("url_AccessDenied.php");
			break;
			
		case "removeColor":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("removeColor"))
				{
					include("act_RemoveColor.php");
					if($ok) { header("location: ".$config['dir']."index.php?fuseaction=admin.colors"); exit; }
				}
			}
			else
				include("url_AccessDenied.php");
			break;
			
		case "orderColor":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("orderColor"))
				{
					if($_REQUEST['dir']=="up")
						include("act_MoveColorUp.php");
					else
						include("act_MoveColorDown.php");
					header("location: ".$config['dir']."index.php?fuseaction=admin.colors");
				}
			}
			else
				include("url_AccessDenied.php");
			break;
			
		case "sortColor":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("orderColor"))
				{
					include("act_SortColor.php");
					exit;
				}
			}
			else
				include("url_AccessDenied.php");
			break;
			
//Crop Multiple Images
		case "cropImages":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("cropImages"))
				{
					include("../VLib/lib_VImage.php");
					include("../VLib/VImage/cls_".$vcfg['vimage']['cls'].".php");
						
					if($_REQUEST['act']=="")
					{
						include("act_CropImagesPrep.php");
						if($ok)
							include("dsp_CropImages.php");
					}
					else
					{
						include("act_CropImages.php");
					}
				}
			}
			else
				include("url_AccessDenied.php");
			break;
			
// Fitting Guide
		case "fittingGuides":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("fittingGuides"))
				{
					if($_REQUEST['act']=="")
					{
						include("qry_FittingGuides.php");
						include("dsp_FittingGuides.php");
					}
					else
					{
						include("act_UpdateFittingGuide.php");
					}
				}
			}
			else
				include("url_AccessDenied.php");
			break;
			
//Logout
//
//
//
		case "logout":
			if($session->check())
			{
				$session->end();
				include("url_Home.php");
			}
			else
				include("url_AccessDenied.php");
			break;

		case "default":
			include("url_AccessDenied.php");
			break;
			
	// Gift Types
		case "giftTypes":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("giftTypes"))
				{
					include("qry_GiftTypes.php");
					include("dsp_GiftTypes.php");
				}
			}
			else
				include("url_AccessDenied.php");
			break;
			
		case "addGiftType":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("addGiftType"))
				{
					if($_REQUEST['act']=="")
					{
						include("dsp_AddGiftType.php");
					}
					else
					{
						include("act_AddGiftType.php");
						if($ok) { header("location: ".$config['dir']."index.php?fuseaction=admin.giftTypes"); exit; }
					}
				}
			}
			else
				include("url_AccessDenied.php");
			break;
			
		case "editGiftType":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("editGiftType"))
				{
					if($_REQUEST['act']=="")
					{
						include("qry_GiftType.php");
						include("dsp_EditGiftType.php");
					}
					else
					{
						include("act_UpdateGiftType.php");
						if($ok) { header("location: ".$config['dir']."index.php?fuseaction=admin.giftTypes"); exit; }
					}
				}
			}
			else
				include("url_AccessDenied.php");
			break;
			
		case "removeGiftType":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("removeGiftType"))
				{
					include("act_RemoveGiftType.php");
					if($ok) { header("location: ".$config['dir']."index.php?fuseaction=admin.giftTypes"); exit; }
				}
			}
			else
				include("url_AccessDenied.php");
			break;
			
		case "sortGiftType":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("orderGiftType"))
				{
					include("act_SortGiftType.php");
					exit;
				}
			}
			else
				include("url_AccessDenied.php");
			break;
			
	// Manage Gift Registry
		case "manageGiftRegistry":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("manageGiftRegistry"))
				{
					if($_REQUEST['act']=="")
					{
						include("qry_ManageGiftRegistry.php");
						include("dsp_ManageGiftRegistry.php");
					}
					else
					{
						include("act_UpdateGiftRegistry.php");
						if($ok) { header("location: ".$config['dir']."index.php?fuseaction=admin.manageGiftRegistry"); exit; }
					}
				}
			}
			else
				include("url_AccessDenied.php");
			break;
			
	// Gift Registry
		case "giftRegistry":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("giftRegistry"))
				{
					include("qry_GiftRegistry.php");
					include("dsp_GiftRegistry.php");
				}
			}
			else
				include("url_AccessDenied.php");
			break;
			
		case "closeGiftRegistryList":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("giftRegistry"))
				{
					include("act_CloseGiftRegistryList.php");
					if($ok) { header("location: ".$config['dir']."index.php?fuseaction=admin.giftRegistry"); exit; }
				}
			}
			else
				include("url_AccessDenied.php");
			break;
			
		case "removeGiftRegistryList":
 			if($session->check())
 			{
				$session->update();
				if($acl->allowed("giftRegistry"))
				{
					include("act_RemoveGiftRegistryList.php");
					if($ok) { header("location: ".$config['dir']."index.php?fuseaction=admin.giftRegistry"); exit; }
				}
 			}
 			else
 				include("url_AccessDenied.php");
 			break;
			
		case "giftRegistryList":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("giftRegistry"))
				{
					include("qry_GiftRegistryList.php");
					include("dsp_GiftRegistryList.php");
				}
			}
			else
				include("url_AccessDenied.php");
			break;

        case "viewGiftRegistry":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("giftRegistry"))
				{
					include("qry_GiftTypes.php");
                    include("qry_AllCountries.php");
                    include("qry_ViewGiftRegistry.php");
					include("dsp_ViewGiftRegistry.php");
				}
			}
			else
				include("url_AccessDenied.php");
			break;

        case "giftRegistryProducts":
			if($session->check())
			{
				$session->update();
				if($acl->allowed("giftRegistry"))
				{
					include("qry_GiftRegistryProducts.php");
					include("dsp_GiftRegistryProducts.php");
				}
			}
			else
				include("url_AccessDenied.php");
			break;
	}
	include("../lib/act_CloseDB.php");
?>
