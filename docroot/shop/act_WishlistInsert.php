<?
	if(!$user_session->check())
	{
		header("location: ".$config['dir'].'login?return_url='.urlencode($config['dir'].'wishlist/insert/'.$_REQUEST['product_id'].'/'.$_REQUEST['option_id'].'&quantity='.$_REQUEST['quantity']));
		exit;
	}
	
	$product_id = $_REQUEST['product_id']+0;
	$option_id = $_REQUEST['option_id']+0;
	$quantity = $_REQUEST['quantity']+0;
	
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
		return;
		
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
?>
