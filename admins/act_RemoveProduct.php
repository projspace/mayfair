<?
	if(file_exists("../mapdir/sitemap.txt"))
		unlink("../mapdir/sitemap.txt");
		
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();
		
	$details=$db->Execute(
		sprintf("
			SELECT
				id
				,imagetype
				,slider_image_type
				,category_id
				,ord
			FROM
				shop_products
			WHERE
				id=%u
		"
			,$_POST['product_id']
		)
	);
	$row=$details->FetchRow();
	if($row['imagetype']!="")
	{
		unlink("../images/product/".$row['id'].".".$row['imagetype']);
		unlink("../images/product/thumb/".$row['id'].".".$row['imagetype']);
		unlink("../images/product/medium/".$row['id'].".".$row['imagetype']);
		unlink("../images/product/original/".$row['id'].".".$row['imagetype']);
		unlink("../images/product/slider/".$row['id'].".".$row['slider_image_type']);
	}
	$db->Execute(
		sprintf("
			DELETE FROM
				shop_products
			WHERE
				id=%u
		"
			,$_POST['product_id']
		)
	);

	//Change order values
	$db->Execute(
		sprintf("
			UPDATE
				shop_products
			SET
				ord=ord-1
			WHERE
				ord>%u
			AND
				category_id=%u
		"
			,$row['ord']
			,$row['category_id']
		)
	);

	$details=$db->Execute(
		sprintf("
			SELECT
				id
				,imagetype
			FROM
				shop_product_images
			WHERE
				product_id=%u
		"
			,$_POST['product_id']
		)
	);
	while($row=$details->FetchRow())
	{
		unlink("../images/product/image".$row['id'].".".$row['imagetype']);
		unlink("../images/product/thumbs/image".$row['id'].".".$row['imagetype']);
	}
	$db->Execute(
		sprintf("
			DELETE FROM
				shop_product_images
			WHERE
				product_id=%u
		"
			,$_POST['product_id']
		)
	);
	$db->Execute(
		sprintf("
			DELETE FROM
				shop_refs
			WHERE
				product_id=%u
		"
			,$_POST['product_id']
		)
	);
	$db->Execute(
		sprintf("
			DELETE FROM
				shop_products
			WHERE
				parent_id=%u
		"
			,$_POST['product_id']
		)
	);
	$db->Execute(
		sprintf("
			UPDATE
				shop_session_cart
			SET
				product_id=1
			WHERE
				product_id=%u
		"
			,$_POST['product_id']
		)
	);
	
	$ok=$db->CompleteTrans();
	if(!$ok)
    	error("There was a problem whilst removing the product, please try again.  If this persists please notify your designated support contact","Database Error");
	else
	{
		$search=new Search($config);
		$search->remove("product",$_POST['product_id']+0);
		
		$sitemap = new Sitemap($config, $db);
		$sitemap->load();
		$sitemap->update();
		$sitemap->save();
	}
?>
