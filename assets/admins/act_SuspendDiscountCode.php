<?
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();

	$db->Execute(
		sprintf("
			UPDATE
				shop_promotional_codes
			SET
				suspended = 1
			WHERE
				id = %u
		"
			,$_POST['code_id']
		)
	);

	$ok=$db->CompleteTrans();
	if(!$ok)
		error("There was a problem whilst suspending the promotional code(s), please try again.  If this persists please notify your designated support contact","Database Error");
?>