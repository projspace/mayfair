<?
	$areas=$db->Execute("
		SELECT
			*
		FROM
			shop_areas
		ORDER BY
			name
		ASC");
?>