<?
	$db->Execute(
		sprintf("
			UPDATE
				shop_sessions
			SET
				delivery_service_code = %s
			WHERE
				session_id = %s
		"
			,$db->Quote($_POST['delivery_service_code'])
			,$db->Quote($session->session_id)
		)
	);
?>