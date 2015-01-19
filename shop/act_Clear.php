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
				shop_session
			SET
				nitems=0
				,total=0
				,weight=0
				,shipping=0
			WHERE
				session_id=%s
		"
			,$db->Quote($session->session_id)
		)
	);
?>