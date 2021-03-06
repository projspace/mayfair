<?
	if(file_exists("../mapdir/sitemap.txt"))
		unlink("../mapdir/sitemap.txt");
		
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();
		
	if($_POST['dir']=="up")
	{
		$ord=$db->Execute(
			sprintf("
				SELECT
					ord
				FROM
					shop_categories
				WHERE
					id=%u
			"
				,$_POST['category_id']
			)
		);
		if($ord->fields['ord']>0)
		{
			$db->Execute(
				sprintf("
					UPDATE
						shop_categories
					SET
						ord=ord+1
					WHERE
						ord=%u
					AND
						parent_id=%u
				"
					,$ord->fields['ord']-1
					,$_POST['parent_id']
				)
			);
			$db->Execute(
				sprintf("
					UPDATE
						shop_categories
					SET
						ord=ord-1
					WHERE
						id=%u
				"
					,$_POST['category_id']
				)
			);
		}
	}
	else
	{
		$ord=$db->Execute(
			sprintf("
				SELECT
					ord
				FROM
					shop_categories
				WHERE
					id=%u
			"
				,$_POST['category_id']
			)
		);
		$max=$db->Execute(
			sprintf("
				SELECT
					MAX(ord) AS max
				FROM
					shop_categories
				WHERE
					parent_id=%u
			"
				,$_POST['parent_id']
			)
		);
		if($ord->fields['ord']<$max->fields['max'])
		{
			$db->Execute(
				sprintf("
					UPDATE
						shop_categories
					SET
						ord=ord-1
					WHERE
						ord=%u
					AND
						parent_id=%u
				"
					,$ord->fields['ord']+1
					,$_POST['parent_id']
				)
			);
			$db->Execute(
				sprintf("
					UPDATE
						shop_categories
					SET
						ord=ord+1
					WHERE
						id=%u
				"
					,$_POST['category_id']
				)
			);
		}
	}
	
	$category_id=$_POST['parent_id'];
	
	$tree=new DBTree($db,"shop_categories");
	$tree->rebuildTree(0,0);
	
	$ok=$db->CompleteTrans();
	if(!$ok)
    	error("There was a problem whilst ordering the category, please try again.  If this persists please notify your designated support contact","Database Error");
?>