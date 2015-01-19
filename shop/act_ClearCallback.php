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
	if(!$order_id)
		return;
		
	$db->Execute(
		sprintf("
			DELETE FROM
				shop_session_cart
			WHERE
				session_id=%s
		"
			,$db->Quote($session->session_id)
		)
	);

	$db->Execute(
		sprintf("
			UPDATE
				shop_sessions
			SET
				nitems=0
				,total=0
				,weight=0
				,packing=0
				,shipping=0
				,multibuy_discount=0
				,promotional_discount=0
				,discount_code = ''
				,delivery_name = ''
				,delivery_email = ''
				,delivery_phone = ''
				,delivery_line1 = ''
				,delivery_line2 = ''
				,delivery_postcode = ''
				,delivery_country_id = ''
				,cvv = ''
			WHERE
				session_id=%s
		"
			,$db->Quote($session->session_id)
		)
	);
	
	$db->Execute(
		sprintf("
			DELETE FROM
				shop_session_txnvars
			WHERE
				session_id=%u
		"
			,$session->sesssion->fields['id']
		)
	);
?>