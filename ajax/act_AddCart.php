<?
	include("../lib/cfg_Config.php");
	include("../lib/adodb/adodb.inc.php");
	include("../lib/act_OpenDB.php");
	include("../lib/lib_Session.php");
	include("../lib/lib_Common.php");
	
	$product_id = $_POST['product_id']+0;
	$option_id = $_POST['option_id']+0;
	$quantity = $_POST['quantity']+0;
	$gift_list_item_id = $_POST['gift_list_item_id']+0;
	
	try
	{
		if(!$gift_list_item_id)
		{
			$gift_list_item_id = $db->Execute(
				sprintf("
					SELECT
						id
					FROM
						gift_list_items
					WHERE
						list_id=%u
					AND
						product_id = %u
					AND
						option_id = %u
				"
					,$session->session->fields['last_gift_list_id']
					,$product_id
					,$option_id
				)
			);
			$gift_list_item_id = $gift_list_item_id->FetchRow();
			$gift_list_item_id = $gift_list_item_id['id']+0;
		}
		
		//Check gift items
		$check = $db->Execute(
			sprintf("
				SELECT
					gli.list_id
					,gli.quantity
					,SUM(sop.quantity) bought
				FROM
					gift_list_items gli
				LEFT JOIN
					shop_order_products sop
				ON
					sop.gift_list_item_id = gli.id
				WHERE
					gli.id=%u
				GROUP BY
					gli.id
			"
				,$gift_list_item_id
			)
		);
		$check = $check->FetchRow();
        $gift_quantity = $check?$check['quantity']-$check['bought']+0:null;
		if($gift_quantity !== null && $gift_quantity < 0)
			$gift_quantity = 0;
			
		if($check['list_id']+0 != $session->session->fields['last_gift_list_id']+0)
			throw new Exception("Please note, all products from gift registry will be removed from your cart.\nPlease checkout if you wish to purchase these goods before clearing the cart.\nAre you sure you want to continue?", 11111);
	
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
					shop_products.price
					,shop_products.discount
					,shop_products.weight
					,shop_products.vat
					,shop_categories.discount AS category_discount
					,shop_categories.discount_trigger AS category_discount_trigger
				FROM
				(
					shop_products
					,shop_categories
				)
				WHERE
					shop_products.id=%u
				AND
					shop_categories.id=shop_products.category_id
			"
				,$product_id
			)
		);
		$product = $product->FetchRow();
		if(!$product)
			throw new Exception('Product not found.', 10001);
			
		$product_option=$db->Execute(
			sprintf("
				SELECT
					*
				FROM
					shop_product_options
				WHERE
					product_id=%u
				AND
					id = %u
			"
				,$product_id
				,$option_id
			)
		);
		$product_option = $product_option->FetchRow();
		if(!$product_option)
			throw new Exception('Product option not found.', 10002);
			
		$price=$product['price']+$product_option['price'];
		$discount=$product['discount'];
		$weight=$product['weight'];
		
		//Check if it's already in the cart
		$incart=$db->Execute(
			sprintf("
				SELECT
					id
					,quantity
				FROM
					shop_session_cart
				WHERE
					product_id=%u
				AND
					option_id=%s
				AND
					session_id=%s
				AND
					parent_id = 0
			"
				,$product_id
				,$option_id
				,$db->Quote($session->session_id)
			)
		);

		//If so, increment by one unless we're given a different quantity to add
		if(!$quantity)
			$quantity=1;
			
		if($quantity + $incart->fields['quantity'] > (($gift_quantity === null)?$product_option['quantity']:$gift_quantity))
			throw new Exception('Quantity requested exceeds current stock availability. Item(s) has not been added to the basket.', 10003);
			
		if($price+0 == 0)
		{
			//Check if there are more than 1 free product
			$free_quantity=$db->Execute(
				$sql=sprintf("
					SELECT
						SUM(shop_session_cart.quantity) quantity
					FROM
						shop_session_cart
					LEFT JOIN
						shop_products
					ON
						shop_products.id = shop_session_cart.product_id
					WHERE
						shop_session_cart.session_id = %s
					AND
						shop_session_cart.parent_id = 0
					AND
						shop_products.price = 0
				"
					,$db->Quote($session->session_id)
				)
			);
			$free_quantity = $free_quantity->FetchRow();
			if($quantity + $free_quantity['quantity'] > 1)
				throw new Exception('Only one giveaway product is allowed per transaction. Your item has not been added to the basket.', 10004);
		}
			
		//Check if we have a category discount
		if($product['category_discount']>0)
		{
			//Check if we are above threshold for category discount
			if($incart->fields['quantity']+$quantity>=$product['category_discount_trigger'])
			{
				$discount+=($price/100)*$product['category_discount'];
			}	
		}

		if($product['vat'])
			$price = $price*(100+VAT)/100;
		
		//$price = round($price);
		
		$db->SetTransactionMode("SERIALIZABLE");
		$db->StartTrans();
		
		if($row=$incart->FetchRow())
		{
			$db->Execute(
				sprintf("
					UPDATE
						shop_session_cart
					SET
						quantity=quantity+%u
						,discount=%f
						,gift_list_item_id=%u
					WHERE
						id=%u
				"
					,$quantity
					,$discount
					,$gift_list_item_id
					,$row['id']
				)
			);
		}
		else
		{
			$db->Execute(
				sprintf("
					INSERT INTO	shop_session_cart (
						session_id
						,product_id
						,time
						,quantity
						,option_id
						,price
						,discount
						,weight
						,gift_list_item_id
					) VALUES (
						%s
						,%u
						,%u
						,%u
						,%u
						,%f
						,%f
						,%f
						,%u
					)
				"
					,$db->Quote($session->session_id)
					,$product_id
					,time()
					,$quantity
					,$option_id
					,$price
					,$discount
					,$weight
					,$gift_list_item_id
				)
			);
			$cart_id=$db->Insert_ID();
		}
		
		$ok=$db->CompleteTrans();
		if(!$ok)
			throw new Exception("There was a problem whilst adding the product to basket, please try again.", 10005);
		
		die(json_encode(array('status'=>true, 'message'=>'')));
	}
	catch(Exception $e)
	{
		if($e->getCode() >= 10000)
			$msg = $e->getMessage();
		else
			$msg = 'There was a problem whilst processing your request, please try again.';
			
		if($e->getCode() == 11111)
			$status = 'clear_gift_registry';
		else
			$status = false;

		die(json_encode(array('status'=>$status, 'message'=>$msg)));
	}
?>