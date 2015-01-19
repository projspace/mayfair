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
	if($ord=="shop_areas.name")
		$ord="shop_countries.name";
	else
		$ord="shop_areas.name";

	$sql_where = array();
	if($_REQUEST['area_id'])
		$sql_where[] = sprintf("shop_countries.area_id = %u", $_REQUEST['area_id']);
	if(count($sql_where))
		$sql_where = implode(' AND ', $sql_where);
	else
		$sql_where = '1';
		
	$countries=$db->Execute(
		sprintf("
			SELECT
				shop_countries.id
				,shop_countries.name
				,shop_areas.name AS area_name
			FROM
				shop_countries
			LEFT JOIN
				shop_areas
			ON
				shop_countries.area_id=shop_areas.id
			WHERE
				%s
			ORDER BY
				%s
			ASC
		"
			,$sql_where
			,$db->Quote($ord)
		)
	);
?>