<?
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();

	$db->Execute(
		sprintf("
			INSERT INTO
				gift_types
			SET
				name = %s
				,ord = IFNULL((SELECT MAX(gt.ord)+1 FROM gift_types gt), 1)
		"
			,$db->Quote(safe($_POST['name']))
		)
	);
	$size_id=$db->Insert_ID();
	
	$ok=$db->CompleteTrans();
	if(!$ok)
		error("There was a problem whilst adding the gift registry type, please try again.  If this problem persists please contact your designated support contact","Database Error");
?>