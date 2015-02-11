<?
	if(file_exists("../mapdir/sitemap.txt"))
		unlink("../mapdir/sitemap.txt");
	
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();	
	
	if($_REQUEST['refid']==0)
	{
        //Get max ord value
        $max=$db->Execute(
                sprintf("
                        SELECT
                                MAX(ord) AS max
                        FROM
                                shop_products
                        WHERE
                                category_id=%u
                "
                        ,$_REQUEST['category_id']
                )
        );

		$db->Execute(
			sprintf("
				UPDATE
					shop_products
				SET
					category_id=%u
					,ord=%u
				WHERE
					id=%u
			"
				,$_REQUEST['category_id']
				,$max->fields['max']+1
				,$_REQUEST['product_id']
			)
		);
	}
	else
	{
		$db->Execute(
			sprintf("
				UPDATE
					shop_refs
				SET
					category_id=%u
				WHERE
					id=%u
			"
				,$_REQUEST['category_id']
				,$_REQUEST['refid']
			)
		);
	}
	
	$ok=$db->CompleteTrans();
	if(!$ok)
    	error("There was a problem whilst moving the product, please try again.  If this persists please notify your designated support contact","Database Error");
?>
