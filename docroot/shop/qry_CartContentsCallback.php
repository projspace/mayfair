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
	$cart=$db->Execute(
		sprintf("
			SELECT
				shop_session_cart.*
				,shop_products.options AS prod_options
				,shop_products.name AS product_name
				,shop_products.code AS product_code
				,shop_sizes.name size
				,shop_widths.name width
				,shop_colors.name color
			FROM
			(
				shop_session_cart
				,shop_products
				,shop_product_options
			)
			LEFT JOIN
				shop_sizes
			ON
				shop_sizes.id = shop_product_options.size_id
			LEFT JOIN
				shop_widths
			ON
				shop_widths.id = shop_product_options.width_id
			LEFT JOIN
				shop_colors
			ON
				shop_colors.id = shop_product_options.color_id
			WHERE
				shop_products.id=shop_session_cart.product_id
			AND
				shop_session_cart.option_id = shop_product_options.id
			AND
				shop_session_cart.product_id = shop_product_options.product_id
			AND
				shop_session_cart.session_id=%s
			ORDER BY
				shop_session_cart.time ASC
		"
			,$db->Quote($session->session_id)
		)
	);
	
	$products=$cart->GetRows();

	//Get any stored TXN Vars
	$txnvars=$db->Execute(
		sprintf("
			SELECT
				shop_session_txnvars.name
				,shop_session_txnvars.value
			FROM
				shop_session_txnvars
				,shop_sessions
			WHERE
				shop_session_txnvars.session_id=shop_sessions.id
			AND
				shop_sessions.session_id=%s
		"
			,$db->Quote($session->session_id)
		)
	);
?>