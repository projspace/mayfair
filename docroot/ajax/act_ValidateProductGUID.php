<?
	include("../lib/cfg_Config.php");
	include("../lib/adodb/adodb.inc.php");
	include("../lib/act_OpenDB.php");
	
	try
	{
		//if(!$user_session->check())
		//	throw new Exception('You are no longer logged in. Please login again.', 10000);
			
		$sql_where = array();
		$sql_where[] = sprintf("guid = %s", $db->Quote($_REQUEST['guid']));
		if(isset($_REQUEST['product_id']))
			$sql_where[] = sprintf("id != %u", $_REQUEST['product_id']);
		
		$results=$db->Execute(
			$sql = sprintf("
				SELECT
					id
				FROM
					shop_products
				WHERE
					%s
				LIMIT 1
			"
				,implode(' AND ', $sql_where)
			)
		);
		if($results->FetchRow())
			throw new Exception('This identifier is not unique.', 10001);
			
		die(json_encode(array('status'=>true, 'message'=>'')));
	}
	catch(Exception $e)
	{
		if($e->getCode() >= 10000)
			$msg = $e->getMessage();
		else
			$msg = 'There was a problem whilst processing your request, please try again.';
			
		die(json_encode(array('status'=>false, 'message'=>$msg)));
	}
?>