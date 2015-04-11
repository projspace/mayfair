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
	$report=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				shop_reports
			WHERE
				id=%u
		"
			,$reportid
		)
	);
?>