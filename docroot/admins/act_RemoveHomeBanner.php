<?
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();
	
	$db->Execute(
		sprintf("
			DELETE FROM
				cms_home_banners
			WHERE
				id=%u
		"
			,$_POST['banner_id']
		)
	);
	
	$ok=$db->CompleteTrans();
	if(!$ok)
		error("There was a problem whilst removing the banner, please try again.  If this persists please notify your designated support contact","Database Error");
	else
		@unlink($config['path'].'images/home_banners/'.$home_banner['id'].'.'.$home_banner['image_type']);
?>