<?
	session_start();
	
	include("../lib/cfg_Config.php");
	include("../lib/adodb/adodb.inc.php");
	include("../lib/act_OpenDB.php");
	include("../lib/lib_Session.php");
	include("../lib/lib_Common.php");
	include("../lib/lib_UserSession.php");
	
	try
	{
		//if(!$user_session->check())
		//	throw new Exception('You are no longer logged in. Please login again.', 10000);
			
		$results=$db->Execute(
			$sql = sprintf("
			(
				SELECT
					shop_promotional_codes.*
				FROM
				(
					shop_promotional_codes
					,shop_user_promotional_codes
				)
				LEFT JOIN
					shop_user_promotional_codes supc
				ON
					supc.code_id = shop_promotional_codes.id
				AND
					supc.account_id = %u
				AND
					supc.order_id != 0
				WHERE
					shop_promotional_codes.code = %s
				AND
					shop_promotional_codes.deleted = 0
				AND
					shop_promotional_codes.suspended = 0
				AND
					shop_promotional_codes.all_users = 0
				AND
					IF(shop_promotional_codes.expiry_date, CURDATE() < shop_promotional_codes.expiry_date, 1)
				AND
					shop_user_promotional_codes.code_id = shop_promotional_codes.id
				AND
					shop_user_promotional_codes.account_id = %u
				AND
					shop_user_promotional_codes.order_id = 0
				AND
					IF(shop_promotional_codes.value_type = 'percent', %f, %f) >= shop_promotional_codes.min_order
				AND
					IF(shop_promotional_codes.gift_list_id > 0, shop_promotional_codes.gift_list_id = %u, 1)
				GROUP BY
					shop_promotional_codes.id
				HAVING
					COUNT(DISTINCT supc.order_id) < shop_promotional_codes.use_count
			)
			UNION ALL
			(
				SELECT
					shop_promotional_codes.*
				FROM
					shop_promotional_codes
				LEFT JOIN
					shop_user_promotional_codes supc
				ON
					supc.code_id = shop_promotional_codes.id
				AND
					supc.account_id = %u
				AND
					supc.order_id != 0
				WHERE
					shop_promotional_codes.code = %s
				AND
					shop_promotional_codes.deleted = 0
				AND
					shop_promotional_codes.suspended = 0
				AND
					shop_promotional_codes.all_users = 1
				AND
					IF(shop_promotional_codes.expiry_date, CURDATE() < shop_promotional_codes.expiry_date, 1)
				AND
					IF(shop_promotional_codes.value_type = 'percent', %f, %f) >= shop_promotional_codes.min_order
				AND
					IF(shop_promotional_codes.gift_list_id > 0, shop_promotional_codes.gift_list_id = %u, 1)
				GROUP BY
					shop_promotional_codes.id
				HAVING
					COUNT(DISTINCT supc.order_id) < shop_promotional_codes.use_count
			)
			"
				,$user_session->account_id
				,$db->Quote($_REQUEST['discount_code'])
				,$user_session->account_id
				,$_SESSION['check_total_discount_percentage']
				,$_SESSION['check_total']
				,$session->session->fields['last_gift_list_id']
				,$user_session->account_id
				,$db->Quote($_REQUEST['discount_code'])
				,$_SESSION['check_total_discount_percentage']
				,$_SESSION['check_total']
				,$session->session->fields['last_gift_list_id']
			)
		);
		if(!$results->FetchRow())
			throw new Exception('This code is not valid.', 10001);
			
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