<?
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();
	
	$db->Execute(
		sprintf("
			DELETE FROM
				cms_pages_images
			WHERE
				id=%u
		"
			,$_POST['image_id']
		)
	);
	
	$ok=$db->CompleteTrans();
	if(!$ok)
		error("There was a problem whilst removing the image, please try again.  If this persists please notify your designated support contact","Database Error");
	else
	{
		foreach($config['size']['page'] as $type=>$size)
		{
			if($type != 'image')
				$dest_file = $config['path'].'images/page/'.$type.'/image_'.$image['id'].'.'.$image['image_type'];
			else
				$dest_file = $config['path'].'images/page/image_'.$image['id'].'.'.$image['image_type'];
			
			@unlink($dest_file);
		}
	}
?>