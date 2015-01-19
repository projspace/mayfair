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
	if($_POST['brand_id']>1)
	{
		$db->SetTransactionMode("SERIALIZABLE");
		$db->StartTrans();
		
		$details=$db->Execute(
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

		$row=$details->FetchRow();
		if($row['imagetype']!="")
		{
			unlink("../images/brand/".$row['id'].".".$row['imagetype']);
			unlink("../images/brand/thumbs/".$row['id'].".".$row['imagetype']);
		}
		$db->Execute(
			sprintf("
				DELETE FROM
					shop_brands
				WHERE
					id=%u
			"
				,$_POST['brand_id']
			)
		);
		//Update orphaned products with default brand
		$db->Execute(
			sprintf("
				UPDATE
					shop_products
				SET
					brand_id=1
				WHERE
					brand_id=%u
			"
				,$_POST['brand_id']
			)
		);
		
		$ok=$db->CompleteTrans();
		if(!$ok)
			error("There was a problem whilst removing the brand, please try again.  If this persists please notify your designated support contact","Database Error");
	}
	else
		error("You cannot delete this brand", "Stop");
?>