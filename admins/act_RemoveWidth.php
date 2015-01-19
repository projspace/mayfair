<?
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();
	
	$db->Execute(
		sprintf("
			DELETE FROM
				shop_widths
			WHERE
				id=%u
		"
			,$_POST['width_id']
		)
	);
	
	$ok=$db->CompleteTrans();
	if(!$ok)
		error("There was a problem whilst removing the product width, please try again.  If this persists please notify your designated support contact","Database Error");
?>