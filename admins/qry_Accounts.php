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
	$accounts=$db->Execute("
		SELECT
			admin_accounts.id
			,admin_accounts.username
			,admin_accounts.email
			,admin_acl_groups.name AS group_name
		FROM
			admin_accounts
			,admin_acl_groups
		WHERE
			admin_acl_groups.id=admin_accounts.group_id
		ORDER BY
			admin_accounts.username
		ASC
	");
?>