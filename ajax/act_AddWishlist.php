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
	
	try
	{
		if(!$user_session->check())
			throw new Exception('You are no longer logged in. Please login again.', 10000);
			
		//Check product is not a linked copy
		$check=$db->Execute(
			sprintf("
				SELECT
					parent_id
				FROM
					shop_products
				WHERE
					id=%u
			"
				,$product_id
			)
		);

		//If it is a linked copy, set the product id to be the parent
		if($check->fields['parent_id']>0)
			$product_id=$check->fields['parent_id'];
		
		//Get the product financial details
		$product=$db->Execute(
			sprintf("
				SELECT
					shop_products.id
				FROM
					shop_products
				WHERE
					shop_products.id=%u
			"
				,$product_id
			)
		);
		$product = $product->FetchRow();
		if(!$product)
			throw new Exception('Product not found. Please login again.', 10001);
			
		//Check if it's already in the cart
		$incart=$db->Execute(
			sprintf("
				SELECT
					id
					,quantity
				FROM
					shop_wishlist
				WHERE
					product_id=%u
				AND
					option_id=%s
				AND
					user_id=%u
			"
				,$product_id
				,$option_id
				,$user_session->account_id
			)
		);

		//If so, increment by one unless we're given a different quantity to add
		if(!$quantity)
			$quantity=1;
		
		$db->SetTransactionMode("SERIALIZABLE");
		$db->StartTrans();
		
		if($row=$incart->FetchRow())
		{
			$db->Execute(
				sprintf("
					UPDATE
						shop_wishlist
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
			$db->Execute(
				sprintf("
					INSERT INTO	shop_wishlist (
						user_id
						,product_id
						,option_id
						,quantity
					) VALUES (
						%u
						,%u
						,%u
						,%u
					)
				"
					,$user_session->account_id
					,$product_id
					,$option_id
					,$quantity
				)
			);
			$wish_id=$db->Insert_ID();
		}
		
		$ok=$db->CompleteTrans();
		if(!$ok)
			throw new Exception("There was a problem whilst adding the product to wishlist, please try again.", 10002);
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