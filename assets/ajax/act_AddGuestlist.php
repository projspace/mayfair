<?
	include("../lib/cfg_Config.php");
	include("../lib/adodb/adodb.inc.php");
	include("../lib/act_OpenDB.php");
	include("../lib/lib_Session.php");
	include("../lib/lib_Common.php");
	include("../lib/lib_UserSession.php");
	
	$product_id = $_POST['product_id']+0;
	$option_id = $_POST['option_id']+0;
	$quantity = $_POST['quantity']+0;
	$list_id = $_POST['list_id']+0;
	
	try
	{
		if(!$user_session->check())
			throw new Exception('You are no longer logged in. Please login again.', 10000);
			
		$option=$db->Execute(
			sprintf("
				SELECT
					id
					,quantity
				FROM
					shop_product_options
				WHERE
					id = %u
			"
				,$option_id
			)
		);
		$option = $option->FetchRow();
			
		//Check if it's already in the cart
		$incart=$db->Execute(
			sprintf("
				SELECT
					id
					,quantity
				FROM
					gift_list_items
				WHERE
					list_id = %u
				AND
					product_id=%u
				AND
					option_id=%u
			"
				,$list_id
				,$product_id
				,$option_id
			)
		);

		//If so, increment by one unless we're given a different quantity to add
		if(!$quantity)
			$quantity=1;
			
		$db->SetTransactionMode("SERIALIZABLE");
		$db->StartTrans();
			
		if($row=$incart->FetchRow())
		{
			if($option['quantity'] - $row['quantity'] < $quantity)
				$quantity = $option['quantity'] - $row['quantity'];
				
			$db->Execute(
				sprintf("
					UPDATE
						gift_list_items
					SET
						quantity=quantity+%u
					WHERE
						id=%u
				"
					,$quantity
					,$row['id']
				)
			);
		}
		else
		{
			if($option['quantity'] < $quantity)
				$quantity = $option['quantity'];

			$db->Execute(
				$sql = sprintf("
					INSERT INTO
						gift_list_items
					SET
						list_id = %u
						,product_id = %u
						,option_id = %u
						,quantity = %u
				"
					,$list_id
					,$product_id
					,$option_id
					,$quantity
				)
			);
			$item_id=$db->Insert_ID();
		}
		
		$ok=$db->CompleteTrans();
		if(!$ok)
			throw new Exception("There was a problem whilst adding the product to guest list, please try again.", 10002);
		die(json_encode(array('status'=>true, 'message'=>'')));
	}
	catch(Exception $e)
	{
		if($e->getCode() >= 10000)
			$msg = $e->getMessage();
		else
			$msg = 'There was a problem whilst processing your request, please try again.';
			
		if($e->getCode() == 10000)
			$status = 'login';
		else
			$status = false;
			
		die(json_encode(array('status'=>$status, 'message'=>$msg)));
	}
?>