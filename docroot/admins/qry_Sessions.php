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
	$sessions=$db->Execute(
		sprintf("
			SELECT
				admin_accounts.username
				,admin_accounts.email
				,admin_sessions.remote_addr
				,admin_sessions.hostname
				,admin_sessions.lastaccess
				,admin_sessions.id
			FROM
				admin_accounts
				,admin_sessions
			WHERE
				admin_sessions.account_id=admin_accounts.id
			AND
				admin_sessions.lastaccess>%u
		"
			,time()-$config['admin']['timeout']
		)
	);

?>