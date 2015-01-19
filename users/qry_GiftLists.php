<?
	$items=$db->Execute(
		sprintf("
			SELECT
				gift_lists.*
			FROM
				gift_lists
			WHERE
				gift_lists.public = 1
			AND
				gift_lists.status = 'pending'
			ORDER BY
				gift_lists.date DESC
		"
		)
	);
?>