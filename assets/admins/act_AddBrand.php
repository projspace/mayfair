<?
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();
	
	$db->Execute(
		sprintf("
			INSERT INTO
				shop_brands
				(
					supplier_id
					,name
					,content
					,hidden
				)
			VALUES
				(
					1
					,%s
					,%s
					,%u
				)
		"
			,$db->Quote($_POST['name'])
			,$db->Quote($_POST['content'][0])
            ,$_POST['hidden']
		)
	);
	$brand_id=$db->Insert_ID();

    if($_FILES['image']['error'] == UPLOAD_ERR_OK)
	{
		$type=$resize->resize($brand_id,"brand");
		if($type)
			$db->Execute(
				sprintf("
					UPDATE
						shop_brands
					SET
						imagetype=%s
					WHERE
						id=%u
				"
					,$db->Quote($type)
					,$brand_id
				)
			);
	}
	
	$ok=$db->CompleteTrans();
	if(!$ok)
    	error("There was a problem whilst adding the brand, please try again.  If this persists please notify your designated support contact","Database Error");
?>