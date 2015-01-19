<?
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();
	
	if($_POST['quantity']+0 == 0)
		$db->Execute(
			sprintf("
				DELETE FROM
					gift_list_items
				WHERE
					list_id=%u
				AND
					id = %u
			"
				,safe($_REQUEST['list_id'])
				,safe($_REQUEST['item_id'])
			)
		);
	else
		$db->Execute(
			sprintf("
				UPDATE
					gift_list_items
				SET
					quantity=%u
				WHERE
					list_id=%u
				AND
					id = %u
			"
				,safe($_POST['quantity'])
				,safe($_REQUEST['list_id'])
				,safe($_REQUEST['item_id'])
			)
		);
	
	$ok=$db->CompleteTrans();
?>