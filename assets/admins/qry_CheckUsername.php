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
	$checkusername=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				admin_accounts
			WHERE
				username=%s
		"
			,$db->Quote($_POST['username'])
		)
	);
	if($checkusername->RecordCount()>0)
		$unique=false;
	else
		$unique=true;
?>