<?
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();
	
	$db->Execute(
		sprintf("
			UPDATE
				shop_brands
			SET
				supplier_id=%u
				,name=%s
				,url=%s
				,content=%s
			WHERE
				id=%u
		"
			,$_POST['supplier_id']
			,$db->Quote($_POST['name'])
			,$db->Quote($_POST['url'])
			,$db->Quote($_POST['content'][0])
			,$_POST['brand_id']
		)
	);
	
	if($_POST['delete']=="on")
	{
		$get=$db->Execute(
			sprintf("
				SELECT
					id
					,imagetype
				FROM
					shop_brands
				WHERE
					id=%u
			"
				,$_POST['brand_id']
			)
		);

		$row=$get->FetchRow();
		if($row['imagetype']!="")
		{
			unlink("../images/brand/".$row['id'].".".$row['imagetype']);
			unlink("../images/brand/thumbs/".$row['id'].".".$row['imagetype']);
		}

		$db->Execute(
			sprintf("
				UPDATE
					shop_brands
				SET
					imagetype=''
				WHERE
					id=%u
			"
				,$_POST['brand_id']
			)
		);
	}

	if($_FILES['image']['error'] == UPLOAD_ERR_OK)
	{
		$type=$resize->resize($_POST['brand_id'],"brand");
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
					,$_POST['brand_id']
				)
			);
	}

	$ok=$db->CompleteTrans();
	if(!$ok)
    	error("There was a problem whilst updating the brand, please try again.  If this persists please notify your designated support contact","Database Error");
?>