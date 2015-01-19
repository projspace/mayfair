<?
	$db->Execute(
		sprintf("
			DELETE FROM
				shop_session_cart
			WHERE
				id=%u
			AND
				session_id=%s
		"
			,safe($_REQUEST['cart_id'])
			,$db->Quote($session->session_id)
		)
	);
	$db->Execute(
		sprintf("
			DELETE FROM
				shop_session_cart
			WHERE
				parent_id=%u
			AND
				session_id=%s
		"
			,safe($_REQUEST['cart_id'])
			,$db->Quote($session->session_id)
		)
	);

    $count = $db->Execute(
        sprintf("
            SELECT
                COUNT(id) count
            FROM
                shop_session_cart
            WHERE
                session_id=%s
        "
            ,$db->Quote($session->session_id)
        )
    );
    $count = $count->FetchRow();
    if(!($count['count']+0))
        $db->Execute(
            sprintf("
                UPDATE
                    shop_sessions
                SET
                    last_gift_list_id=0
                WHERE
                    session_id=%s
            "
                ,$db->Quote($session->session_id)
            )
        );
?>
