<?
	/**
	 * e-Commerce System
	 * Copyright (c) 2002-2006 Philip John, All Rights Reserved.
	 * Author	: Philip John
	 * Version	: 6.0
	 *
	 * PROPRIETARY/CONFIDENTIAL.  Use is subject to license terms.
	 */
?>
<?
	include("lib/adodb/adodb.inc.php");
	include("lib/act_OpenDB.php");
	include("lib/lib_Session.php");
	include("lib/lib_Smarty.php");
	include("lib/lib_Elements.php");
	include("lib/lib_CustomElements.php");
	$elems=new CustomElements($db,$smarty,$config,$session->session_id);
	include("lib/lib_Common.php");
	include("lib/lib_UserSession.php");

	if(!isset($category_id))
		$category_id=1;
		
	$vat=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				shop_variables
			WHERE
				name = 'vat'
		"
		)
	);
	$vat = $vat->FetchRow();
	define('VAT', $vat['value']);

	if(TESTCOOKIE)
	{
		$url=$_SERVER['REQUEST_URI'];
		if(strpos($url,"?")!==false)
			$url=$url."&";
		else
			$url=$url."?";
		$url=$url.$config['shop']['session_id']."=".$session->session_id;
		header("location: $url");exit;
	}

	if(!SEARCHENGINE)
	{
		if($session->session->fields['area_id']==0)
		{
			$area_id=1;
			include("act_SelectArea.php");
			//$Fusebox['fuseaction']="selectArea";
		}
	}

	if(!USECOOKIE && !SEARCHENGINE)
	{
		$sid_amp="&amp;".urlencode($config['shop']['session_id'])."=".urlencode($$config['shop']['session_id']);
		$sid="?".urlencode($config['shop']['session_id'])."=".urlencode($$config['shop']['session_id']);
		$sid_form="<input type=\"hidden\" name=\"".$config['shop']['session_id']."\" value=\"".$$config['shop']['session_id']."\" />";
	}

	if($user_session->check())
		$user_session->update();
	
	$trail = array('menu'=>array(), 'submenu'=>array());
	$trail['menu'][] = array('url'=>$config['dir'], 'name'=>'Shop');
	
	switch($Fusebox['fuseaction'])
	{
		case "main":
		case "Fusebox.defaultFuseaction":
			include("qry_DefaultPage.php");
			include("qry_PageContent.php");
			include("qry_Home.php");
			include("dsp_Home.php");
			//include("dsp_Home.new.php");
			break;

		case "content":
			$pageid=$_GET['pageid'];
			include("qry_PageContent.php");
			switch($page['layout_type'])
			{
				case 'all-stars':
					include("dsp_PageContentAllStars.php");
					break;
				case 'fitting':
					include("dsp_PageContentFitting.php");
					break;
				default:
					include("dsp_PageContent.php");
					break;
			}
			break;

		case "import_excel":
			include("import_excel.php");
		break;
		
		case "sitemap":
			include("qry_Sitemap.php");
			include("dsp_Sitemap.php");
			break;

		case "selectArea":
			if($act=="")
			{
				include("qry_Areas.php");
				include("dsp_SelectArea.php");
			}
			else
			{
				include("act_SelectArea.php");
				header("location: /");
			}

        case "catalogues":
            include("qry_Catalogues.php");

            if($_GET['act'] == 'submit'){
                include("act_SubmitCatalogues.php");
            }

            include("dsp_Catalogues.php");
            break;

        case "press":
            include("qry_Press.php");
            include("dsp_Press.php");
            break;

        case "viewPress":
            include("qry_Press.php");
            include("dsp_ViewPress.php");
            break;

        case "home_fs":
            include("dsp_HomeFS.php");
            break;

		default:
			break;
	}
?>
