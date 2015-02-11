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
					,url
					,content
				)
			VALUES
				(
					%u
					,%s
					,%s
					,%s
				)
		"
			,$_POST['supplier_id']
			,$db->Quote($_POST['name'])
			,$db->Quote($_POST['url'])
			,$db->Quote($_POST['content'][0])
		)
	);
	$brand_id=$db->Insert_ID();
	
	$ok=$db->CompleteTrans();
	if(!$ok)
    	error("There was a problem whilst adding the brand, please try again.  If this persists please notify your designated support contact","Database Error");
	else
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
?>