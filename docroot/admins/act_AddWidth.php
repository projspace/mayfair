<?
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();

	$db->Execute(
		sprintf("
			INSERT INTO
				shop_widths
			SET
				name = %s
				,code = %s
				,ord = IFNULL((SELECT MAX(sw.ord)+1 FROM shop_widths sw), 1)
		"
			,$db->Quote(safe($_POST['name']))
			,$db->Quote(safe($_POST['code']))
		)
	);
	$width_id=$db->Insert_ID();
	
	$ok=$db->CompleteTrans();
	if(!$ok)
		error("There was a problem whilst adding the product width, please try again.  If this problem persists please contact your designated support contact","Database Error");
?>