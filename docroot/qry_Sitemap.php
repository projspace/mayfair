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
	$pages=$db->Execute("SELECT * FROM cms_pages WHERE deleted = 0 AND hidden = 0 AND sitemap = 1 ORDER BY name ASC");

	if(!file_exists("mapdir/sitemap.txt"))
		$shop=shop_struct(1);
	else
		$shop=unserialize(file_get_contents("mapdir/sitemap.txt"));

	function shop_struct($parent_id)
	{
		global $db;
		$parent=$db->Execute(
			sprintf("
				SELECT
					ord
				FROM
					shop_categories
				WHERE
					id=%u
			"
				,$parent_id
			)
		);
		$children=$db->Execute(
			sprintf("
				SELECT
					id
					,name
				FROM
					shop_categories
				WHERE
					parent_id=%u
				ORDER BY
					%s ASC
			"
				,$parent_id
				,($parent->fields['ord']==1) ? "name" : "ord"
			)
		);
		$count=0;
		while($row=$children->FetchRow())
		{
			$ret[$count]=$row;
			$products=$db->Execute("SELECT id,category_id,name,guid FROM shop_products WHERE category_id='".$row["id"]."' AND id>1");
			$ret[$count]["products"]=$products->GetRows();

			$get=shop_struct($row["id"]);
			if(is_array($get))
				if(count($get)>0)
					$ret[$count]["children"]=$get;
			$count++;
		}
		if($parent_id==1)
		{
			$fp=fopen("mapdir/sitemap.txt","w");
			fwrite($fp,serialize($ret));
			fclose($fp);
		}
		return $ret;
	}
?>