<?
	if(file_exists("../mapdir/sitemap.txt"))
		unlink("../mapdir/sitemap.txt");
		
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();
		
	$db->Execute(
		sprintf("
			UPDATE
				shop_products
			SET
				hidden=%u
			WHERE
				id=%u
		"
			,$_POST['hidden']
			,$_POST['product_id']
		)
	);
	
	$ok=$db->CompleteTrans();
	if(!$ok)
    	error("There was a problem whilst updating the product state, please try again.  If this persists please notify your designated support contact","Database Error");
?>