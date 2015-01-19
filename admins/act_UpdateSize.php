<?
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();

	$db->Execute(
		sprintf("
			UPDATE
				shop_sizes
			SET
				name = %s
				,alt = %s
			WHERE
				id = %u
		"
			,$db->Quote(safe($_POST['name']))
			,$db->Quote(safe($_POST['alt']))
			,$_REQUEST['size_id']
		)
	);

	$ok=$db->CompleteTrans();
	if(!$ok)
		error("There was a problem whilst updating the product size, please try again.  If this problem persists please contact your designated support contact","Database Error");
?>