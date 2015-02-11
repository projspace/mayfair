<?
	$wish_id = $_REQUEST['wish_id']+0;
	
	$source=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				shop_wishlist
			WHERE
				id=%u
		"
			,$wish_id
		)
	);
	$source = $source->FetchRow();
	if(!$source)
		return;
		
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
			,$source['product_id']
		)
	);
	$product = $product->FetchRow();
	if(!$product)
		return;
		
	$price=$product['price'];
	$discount=$product['discount'];
	$weight=$product['weight'];
		
	$destination=$db->Execute(
		sprintf("
			SELECT
				id
			FROM
				shop_session_cart
			WHERE
				product_id=%u
			AND
				option_id=%u
			AND
				session_id=%s
		"
			,$source['product_id']
			,$source['option_id']
			,$db->Quote($session->session_id)
		)
	);
	$destination = $destination->FetchRow();
	
	if($product['category_discount']>0)
	{
		//Check if we are above threshold for category discount
		if($destination['quantity']+$source['quantity']>=$product['category_discount_trigger'])
		{
			$discount+=($price/100)*$product['category_discount'];
		}	
	}
	
	if($product['vat'])
		$price = $price*(100+VAT)/100;
	
	//$price = round($price);
	
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();
	
	if($destination)
	{
		$db->Execute(
			sprintf("
				UPDATE
					shop_session_cart
				SET
					quantity=quantity+%u
					,discount=%f
				WHERE
					id=%u
			"
				,$source['quantity']
				,$discount
				,$destination['id']
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
					,option_id
					,quantity
					,time
					,price
					,discount
					,weight
				) VALUES (
					%s
					,%u
					,%u
					,%u
					,%u
					,%f
					,%f
					,%f
				)
			"
				,$db->Quote($session->session_id)
				,$source['product_id']
				,$source['option_id']
				,$source['quantity']
				,time()
				,$price
				,$discount
				,$weight
			)
		);
		$cart_id=$db->Insert_ID();
	}
	
	$db->Execute(
		sprintf("
			DELETE FROM
				shop_wishlist
			WHERE
				id=%u
		"
			,$wish_id
		)
	);
	
	$ok=$db->CompleteTrans();
?>
