<?
	include("../lib/cfg_Config.php");
	include("../lib/cfg_Options.php");
	include("../lib/adodb/adodb.inc.php");
	include("../lib/act_OpenDB.php");
	include("../lib/lib_Session.php");
	
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

	$db->Execute(
		$sql = sprintf("
			DELETE FROM
				shop_session_cart
			WHERE
				session_id=%s
			AND
				gift_list_item_id != 0
		"
			,$db->Quote($session->session_id)
			,$gift_list['id']
		)
	);
?>