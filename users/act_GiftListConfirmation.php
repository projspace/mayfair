<?
	$db->Execute(
		$sql = sprintf("
			DELETE FROM
				shop_session_cart
			WHERE
				session_id=%s
			AND
				gift_list_item_id NOT IN (SELECT id FROM gift_list_items WHERE list_id = %u)
		"
			,$db->Quote($session->session_id)
			,$gift_list['id']
		)
	);
?>