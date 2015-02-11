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
	//Get the product financial details
	$product=$db->Execute(
		sprintf("
			SELECT
				shop_products.price
				,shop_products.discount
				,shop_products.weight
				,shop_products.options
				,shop_categories.discount AS category_discount
				,shop_categories.discount_trigger AS category_discount_trigger
				,shop_session_cart.quantity AS quantity
				,shop_session_cart.price AS cart_price
				,shop_session_cart.discount AS cart_discount
			FROM
				shop_products
				,shop_categories
				,shop_session_cart
			WHERE
				shop_session_cart.id=%u
			AND
				shop_session_cart.session_id=%s
			AND
				shop_products.id=shop_session_cart.product_id
			AND
				shop_categories.id=shop_products.category_id
		"
			,$cart_id
			,$db->Quote($session->session_id)
		)
	);
	
	if($product->fields['quantity']==1)
	{
		$db->Execute(
			sprintf("
				DELETE FROM
					shop_session_cart
				WHERE
					id=%u
				AND
					session_id=%s
			"
				,$cart_id
				,$db->Quote($session->session_id)
			)
		);
	}
	else
	{
		$discount=$product->fields['cart_discount'];
		$price=$product->fields['cart_price'];
		
		//Check if we have a category discount
		if($product->fields['category_discount']>0)
		{
			//Check if we are above threshold for category discount
			if($product->fields['quantity']==$product->fields['category_discount_trigger'])
			{
				$discount-=($price/100)*$product->fields['category_discount'];
			}	
		}
		
		$db->Execute(
			sprintf("
				UPDATE
					shop_session_cart
				SET
					quantity=quantity-1
					,discount=%f
				WHERE
					session_id=%s
				AND
					quantity>0
				AND
					id=%u
			"
				,$discount
				,$db->Quote($session->session_id)
				,$cart_id
			)
		);
	}
?>
