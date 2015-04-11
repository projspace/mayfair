<?
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();
	
	$db->Execute(
		sprintf("
			DELETE FROM
				shop_category_filters
			WHERE
				id=%u
		"
			,$_POST['filter_id']
		)
	);
	
	$ok=$db->CompleteTrans();
	if(!$ok)
		error("There was a problem whilst removing the filter, please try again.  If this persists please notify your designated support contact","Database Error");
?>