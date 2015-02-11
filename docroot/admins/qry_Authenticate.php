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
	//$password=md5($password);
	$check=$db->Execute(
		$sql = sprintf("
			SELECT
				id
			FROM
				admin_accounts
			WHERE
				username=%s
			AND
				password=%s
		"
			,$db->Quote($_POST['username'])
			,$db->Quote($_POST['password'])
		)
	);
	if($check->EOF)
		$auth=false;
	else
		$auth=true;
?>