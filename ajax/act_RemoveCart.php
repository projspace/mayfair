<?
	include("../lib/cfg_Config.php");
	include("../lib/adodb/adodb.inc.php");
	include("../lib/act_OpenDB.php");
	include("../lib/lib_Session.php");
	include("../lib/lib_Elements.php");
	include("../lib/lib_CustomElements.php");
	$elems=new CustomElements($db,$smarty,$config,$session->session_id);
	include("../lib/lib_Common.php");
	
	try
	{
		$db->SetTransactionMode("SERIALIZABLE");
		$db->StartTrans();
		
		include("../shop/act_Remove.php");
		
		$ok=$db->CompleteTrans();
		if(!$ok)
			throw new Exception("There was a problem whilst removing the product from basket, please try again.");
			
		die('TRUE');
	}
	catch(Exception $e)
	{
		die('FALSE');
	}
?>