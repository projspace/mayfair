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
	$product=$db->Execute(
		sprintf("
			SELECT
				shop_products.price
				,shop_products.weight
				,shop_products.options
				,shop_products.discount
			FROM
				shop_products
				,shop_session_cart
			WHERE
				shop_products.id=shop_session_cart.product_id
			AND
				shop_session_cart.id=%u
			AND
				shop_session_cart.session_id=%s
		"
			,$cart_id
			,$db->Quote($session->session_id)
		)
	);

	$price=$product->fields['price']-$product->fields['discount'];
	$weight=$product->fields['weight'];
	$options=unserialize($product->fields['options']);

	for($i=0;$i<OPTIONS;$i++)
	{
		$seloption[$i]=$option[$i];
		$price+=$options[$i]["price"][$option[$i]];
		$weight+=$options[$i]["weight"][$option[$i]];
	}
	$option=serialize($seloption);

	$db->Execute(
		sprintf("
			UPDATE
				shop_session_cart
			SET
				options=%s
				,price=%f
				,weight=%f
			WHERE
				session_id=%s
			AND
				id=%u
		"
			,$db->Quote($option)
			,$price
			,$weight
			,$db->Quote($session->session_id)
			,$cart_id
		)
	);
?>
