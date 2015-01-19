<?
	/**
	 * e-Commerce System
	 * Copyright (c) 2002-2006 Philip John, All Rights Reserved.
	 * Author	: Philip John
	 * Version	: 6.0
	 *
	 * PROPRIETARY/CONFIDENTIAL.  Use is subject to license terms.
	 */
?>
<?
	if(file_exists("../mapdir/sitemap.txt"))
		unlink("../mapdir/sitemap.txt");
		
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();
		
	if($_REQUEST['parent_id']!=$_REQUEST['category_id'])
	{
		$max=$db->Execute(
			sprintf("
				SELECT
					MAX(ord) AS max
				FROM
					shop_categories
				WHERE
					parent_id=%u
			"
				,$_REQUEST['parent_id']
			)
		);

		if($max->fields['max']=="")
			$max=0;
		else
			$max=$max->fields['max']+1;

		$ord=$db->Execute(
			sprintf("
				SELECT
					ord
					,parent_id
				FROM
					shop_categories
				WHERE
					id=%u
			"
				,$_REQUEST['category_id']
			)
		);

		$db->Execute(
			sprintf("
				UPDATE
					shop_categories
				SET
					parent_id=%u
					,ord=%u
				WHERE
					id=%u
				AND
					parent_id>0
			"
				,$_REQUEST['parent_id']
				,$max
				,$_REQUEST['category_id']
			)
		);

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
				,$ord->fields['ord']
				,$ord->fields['parent_id']
			)
		);
	}
	$category_id=$_REQUEST['parent_id'];
	
	$tree=new DBTree($db,"shop_categories");
	$tree->rebuildTree(0,0);
	
	$ok=$db->CompleteTrans();
	if(!$ok)
    	error("There was a problem whilst updating the brand, please try again.  If this persists please notify your designated support contact","Database Error");
?>