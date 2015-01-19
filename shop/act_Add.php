<?
	/**
	 * e-Commerce System Data Feed/Export Plugin
	 * Copyright (c) 2002-2006 Philip John, All Rights Reserved.
	 * Author	: Philip John
	 * Version	: 6.0
	 *
	 * PROPRIETARY/CONFIDENTIAL.  Use is subject to license terms.
	 */
?>
<?
	$product_id = $_POST['product_id']+0;
	$quantity = $_POST['quantity']+0;
	$option = $_POST['option'];
	
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
				,shop_products.options
				,shop_products.vat
				,shop_products.buy_1_get_1_free
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
			AND
				shop_products.parent_id = 0
		"
			,$product_id
		)
	);
	$product = $product->FetchRow();
	if(!$product)
		return;
		
	$price=$product['price'];
	$discount=$product['discount'];
	$weight=$product['weight'];
	$options=unserialize($product['options']);

	//Construct options array
	for($i=0;$i<OPTIONS;$i++)
	{
		$seloption[$i]=(int) $option[$i];
		$price+=$options[$i]["price"][$option[$i]];
		$weight+=$options[$i]["weight"][$option[$i]];
	}
	$option=serialize($seloption);

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
				options=%s
			AND
				session_id=%s
			AND
				parent_id = 0
		"
			,$product_id
			,$db->Quote($option)
			,$db->Quote($session->session_id)
		)
	);

	//If so, increment by one unless we're given a different quantity to add
	if(!$quantity)
		$quantity=1;
		
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
		return;

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
	
	if($row=$incart->FetchRow())
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
				,$quantity
				,$discount
				,$row['id']
			)
		);
		if($product['buy_1_get_1_free'])
			$db->Execute(
				sprintf("
					UPDATE
						shop_session_cart
					SET
						quantity=quantity+%u
					WHERE
						parent_id = %u
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
				INSERT INTO	shop_session_cart (
					session_id
					,product_id
					,time
					,quantity
					,options
					,price
					,discount
					,weight
				) VALUES (
					%s
					,%u
					,%u
					,%u
					,%s
					,%f
					,%f
					,%f
				)
			"
				,$db->Quote($session->session_id)
				,$product_id
				,time()
				,$quantity
				,$db->Quote($option)
				,$price
				,$discount
				,$weight
			)
		);
		$cart_id=$db->Insert_ID();
		if($product['buy_1_get_1_free'] && $cart_id)
			$db->Execute(
				sprintf("
					INSERT INTO	shop_session_cart (
						session_id
						,parent_id
						,product_id
						,time
						,quantity
						,options
						,price
						,discount
						,weight
					) VALUES (
						%s
						,%u
						,%u
						,%u
						,%u
						,%s
						,%f
						,%f
						,%f
					)
				"
					,$db->Quote($session->session_id)
					,$cart_id
					,$product_id
					,time()
					,$quantity
					,$db->Quote($option)
					,0
					,0
					,$weight
				)
			);
			
	}
?>
