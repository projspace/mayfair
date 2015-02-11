<?
	include("../lib/cfg_Config.php");
	include("../lib/cfg_Options.php");
	include("../lib/adodb/adodb.inc.php");
	include("../lib/act_OpenDB.php");
	include("../lib/lib_Session.php");
	include("../lib/lib_UserSession.php");
	include("../lib/lib_Common.php");
	
	if($user_session->check())
		$user_session->update();
	
	$_REQUEST['discount_code'] = $session->session->fields['discount_code'];
	
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
	
	require "../shop/act_UpdateCartDetails.php";
	$product_price = $price;
	require "../shop/qry_CartContents.php";
	die('var product_price='.($product_price+0).'; vars= '.json_encode($vars));
?>