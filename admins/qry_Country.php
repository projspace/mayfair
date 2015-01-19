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
	$country=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				shop_countries
			WHERE
				id = %u
		"
			,$_REQUEST['country_id']
		)
	);
	$country = $country->FetchRow();
?>