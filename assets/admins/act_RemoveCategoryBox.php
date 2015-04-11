<?
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();
	
	$db->Execute(
		sprintf("
			DELETE FROM
				shop_category_boxes
			WHERE
				id=%u
		"
			,$_POST['box_id']
		)
	);
	
	$ok=$db->CompleteTrans();
	if(!$ok)
		error("There was a problem whilst removing the box, please try again.  If this persists please notify your designated support contact","Database Error");
	else
		while($row = $items->FetchRow())
			@unlink($config['path'].'images/box_items/'.$row['id'].'.'.$row['image_type']);
?>