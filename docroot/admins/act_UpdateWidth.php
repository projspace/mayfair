<?
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();

	$db->Execute(
		sprintf("
			UPDATE
				shop_widths
			SET
				name = %s
				,code = %s
			WHERE
				id = %u
		"
			,$db->Quote(safe($_POST['name']))
			,$db->Quote(safe($_POST['code']))
			,$_REQUEST['width_id']
		)
	);

	$ok=$db->CompleteTrans();
	if(!$ok)
		error("There was a problem whilst updating the product width, please try again.  If this problem persists please contact your designated support contact","Database Error");
?>