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
				,parent_id
				,ord
			FROM
				shop_categories
			WHERE
				id=%u
		"
			,$_POST['category_id']
		)
	);

	$row=$details->FetchRow();
	if($row['imagetype']!="")
	{
		unlink("../images/category/".$row['id'].".".$row['imagetype']);
		unlink("../images/category/thumbs/".$row['id'].".".$row['imagetype']);
		unlink("../images/category/slider/".$row['id'].".".$row['slider_image_type']);
	}
	
	$tree=new DBTree($db,"shop_categories");
	$tree->removePage($row['id']);
	
	//Delete category
	$db->Execute(
		sprintf("
			DELETE FROM
				shop_categories
			WHERE id=%u
		"
			,$_POST['category_id']
		)
	);
	//Delete restrictions
	$db->Execute(
		sprintf("
			DELETE FROM
				shop_category_restrictions
			WHERE
				category_id=%u
		"
			,$_POST['category_id']
		)
	);
	//Update ordering
	$db->Execute(
		sprintf("
			UPDATE
				shop_categories
			SET
				ord=ord-1
			WHERE
				ord>%u
			AND
				parent_id=%u
		"
			,$row['ord']
			,$row['parent_id']
		)
	);
	//Move orphaned products to parent category
	$db->Execute(
		sprintf("
			UPDATE
				shop_products
			SET
				category_id=%u
			WHERE
				category_id=%u
		"
			,$row['parent_id']
			,$_POST['category_id']
		)
	);
	//Move orphaned references to parent category
	$db->Execute(
		sprintf("
			UPDATE
				shop_refs
			SET
				category_id=%u
			WHERE
				category_id=%u
		"
			,$row['parent_id']
			,$_POST['category_id']
		)
	);
	//Move orphaned categories to parent category and update order field
	$max=$db->Execute(
		sprintf("
			SELECT
				MAX(ord) AS max
			FROM
				shop_categories
			WHERE
				parent_id=%u
		"
			,$row['parent_id']
		)
	);
	$db->Execute(
		sprintf("
			UPDATE
				shop_categories
			SET
				parent_id=%u
				,ord=ord+%u
			WHERE
				parent_id=%u
		"
			,$row['parent_id']
			,$max->fields['max']+1
			,$_POST['category_id']
		)
	);
	$category_id=$row['parent_id'];
	
	$ok=$db->CompleteTrans();
	if(!$ok)
    	error("There was a problem whilst removing the category, please try again.  If this persists please notify your designated support contact","Database Error");
?>