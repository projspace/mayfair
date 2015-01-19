<?
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();

	$db->Execute(
		sprintf("
			INSERT INTO
				shop_sizes
			SET
				name = %s
				,alt = %s
				,ord = IFNULL((SELECT MAX(ss.ord)+1 FROM shop_sizes ss), 1)
		"
			,$db->Quote(safe($_POST['name']))
			,$db->Quote(safe($_POST['alt']))
		)
	);
	$size_id=$db->Insert_ID();
	
	$ok=$db->CompleteTrans();
	if(!$ok)
		error("There was a problem whilst adding the product size, please try again.  If this problem persists please contact your designated support contact","Database Error");
?>