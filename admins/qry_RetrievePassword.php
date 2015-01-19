<?
	/**
	 * e-Commerce System
	 * Copyright (c) 2002-2006 Philip John, All Rights Reserved.
	 * Author	: Philip John
	 * Version	: 6.0
	 *
	 * PROPRIETARY/CONFIDENTIAL.  Use is subject to license terms.
	 */
?>
<?
	$account=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				admin_accounts
			WHERE
				username=%s
			OR
				email=%s
		"
			,$db->Quote($username)
			,$db->Quote($email)
		)
	);
	if(!$account->EOF)
		$found=true;
	else
		$found=false;
?>