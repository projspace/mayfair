<?
        /**
         * e-Commerce System
         * Copyright (c) 2002-2006 Philip John, All Rights Reserved.
         * Author        : Philip John
         * Version        : 6.0
         *
         * PROPRIETARY/CONFIDENTIAL.  Use is subject to license terms.
         */
?>
<?php
        $Fusebox["layoutDir"]="layout/";
        if($Fusebox["circuit"]=="admin")
        {
                $Fusebox["layoutDir"].="admin/";
                if($Fusebox["fuseaction"]=="login" || $Fusebox["fuseaction"]=="doLogin" || $Fusebox["fuseaction"]=="logout" || $Fusebox["fuseaction"]=="accessDenied" || $Fusebox["fuseaction"]=="sendPassword")
                        $Fusebox["layoutFile"]="lay_Plain.php";
				else if($Fusebox["fuseaction"]=="movePage")
					$Fusebox["layoutFile"]="lay_PopupPages.php";
                else if($Fusebox["fuseaction"]=="move" && $act=="")
                        $Fusebox["layoutFile"]="lay_Popup.php";
                else if($Fusebox["fuseaction"]=="help")
                        $Fusebox["layoutFile"]="lay_Help.php";
                else if($Fusebox["fuseaction"]=="moveCategory" && $act=="")
                        $Fusebox["layoutFile"]="lay_PopupCategory.php";
                else
                        $Fusebox["layoutFile"]="lay_Admin.php";
        }
        else if($Fusebox["circuit"]=="home")
        {
			$Fusebox["layoutDir"].="templates/".$config['layout']."/";
			//if(!in_array($Fusebox['fuseaction'], array("content", "main", "Fusebox.defaultFuseaction")))
			//{
                $Fusebox["layoutFile"]="lay_SiteLayout.php";
				if($Fusebox["fuseaction"]=="404")
					$Fusebox["layoutFile"]="lay_404.php";

                if(in_array($Fusebox["fuseaction"], array("home_fs")))
                        $Fusebox["layoutFile"]="lay_Plain.php";
                if($Fusebox['fuseaction']!="main")
                        if(file_exists($config['path'].$config['dir']."layout/templates/".$config['layout']."/lay_SiteLayoutInner.php"))
                                $Fusebox["layoutFile"]="lay_SiteLayoutInner.php";
                else if($Fusebox['fuseaction']=="selectArea")
                        $Fusebox["layoutFile"]="lay_SelectArea.php";
			//}
        }
        else if($Fusebox["circuit"]=="user")
        {
        		
                $Fusebox["layoutDir"].="templates/".$config['layout']."/";
				if((in_array($Fusebox["fuseaction"], array("register","login","forgottenPassword",'addAddress','editAddress','editGiftRegistryListItemQuantity','editAuthorizePaymentProfile','addAuthorizePaymentProfile','editAuthorizeShippingProfile','addAuthorizeShipingProfile')) && isset($_REQUEST['ajax']))
                    || in_array($Fusebox["fuseaction"], array('giftRegistry','giftRegistryList')))
					$Fusebox["layoutFile"]="lay_Plain.php";
				elseif($Fusebox['fuseaction'] == 'shopsSearch')
					$Fusebox["layoutFile"]="lay_GoogleMaps.php";
				else
					$Fusebox["layoutFile"]="lay_SiteLayout.php";
        }
        else if($Fusebox["circuit"]=="shop")
        {
                $Fusebox["layoutDir"].="templates/".$config['layout']."/";
				if(in_array($Fusebox["fuseaction"], array("callback","sign_up")))
                        $Fusebox["layoutFile"]="lay_Callback.php";
                else if(in_array($Fusebox["fuseaction"], array("image","options","360_view","addReview","video","advancedSearch","quick_product")))
                        $Fusebox["layoutFile"]="lay_Plain.php";
                else
                        $Fusebox["layoutFile"]="lay_ShopLayout.php";
        }
?>
