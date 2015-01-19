<?
	$items=$db->Execute(
		sprintf("
			SELECT
				gift_lists.*
			FROM
				gift_lists
			WHERE
				gift_lists.account_id = %u
			ORDER BY
				gift_lists.date DESC
		"
			,$user_session->account_id
		)
	);
?>