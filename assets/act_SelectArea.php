<?
	$db->Execute(
		sprintf("
			UPDATE
				shop_sessions
			SET
				area_id=%u
			WHERE
				session_id=%s
		"
			,$area_id
			,$db->Quote($session->session_id)
		)
	);
?>