<?
	$types=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				gift_types
			ORDER BY
				ord ASC
		"
		)
	);
?>