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
	createMap();

	function createMap($trail=false,$id=0)
	{
		global $db;
		if(!$trail)
		{
			$trail[0]['name']="Home";
			$trail[0]['url']="index.php";
		}

		$result=$db->Execute(
			sprintf("
				SELECT
					id
					,name
				FROM
					shop_categories
				WHERE
					parent_id=%u
			"
				,$id
			)
		);

		while($row=$result->FetchRow())
		{
			$temptrail=$trail;
			$temptrail[count($trail)]['name']=$row['name'];
			$temptrail[count($trail)]['id']=$row['id'];
			$temptrail[count($trail)]['url']="index.php/fuseaction/shop.category/category_id/".$row['id'];
			$db->Execute(
				sprintf("
					UPDATE
						shop_categories
					SET
						trail=%s
					WHERE
						id=%u
				"
					,$db->Quote(serialize($temptrail))
					,$row['id']
				)
			);
			createMap($temptrail,$row['id']);
		}
	}
?>