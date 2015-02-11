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

	$keys=array_keys($details['txnvars']);
	foreach($keys as $key)
	{
		$db->Execute(
			sprintf("
				INSERT INTO
					shop_session_txnvars (
						session_id
						,name
						,value
					) VALUES (
						%u
						,%s
						,%s
					)
			"
				,$session->session->fields['id']
				,$db->Quote($key)
				,$db->Quote($details['txnvars'][$key])
			)
		);
	}
?>