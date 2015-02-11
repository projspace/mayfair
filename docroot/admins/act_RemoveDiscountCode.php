<?
	switch(strtolower(trim($_POST["type"])))
	{
		case 'all':
			$sql = '1';
			break;
		case 'expired':
			$sql = 'expiry_date <= CURDATE()';
			break;
		default:
			$sql = sprintf("id = %u", $_POST['code_id']);
			break;
	}
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();

	/*$db->Execute(
		sprintf("
			UPDATE
				shop_promotional_codes
			SET
				deleted = 1
			WHERE
				".$sql
		)
	);*/
	$db->Execute(
		sprintf("
			DELETE FROM
				shop_promotional_codes
			WHERE
				".$sql
		)
	);

	$ok=$db->CompleteTrans();
	if(!$ok)
		error("There was a problem whilst removing the promotional code(s), please try again.  If this persists please notify your designated support contact","Database Error");
?>