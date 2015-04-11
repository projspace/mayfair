<?
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();

	foreach((array)$_REQUEST['product_ids'] as $product_id)
		$db->Execute(
			$sql=sprintf("
				INSERT INTO
					shop_product_similar
				SET
					product_id = %u
					,similar_product_id = %u
			"
				,$_REQUEST['product_id']
				,$product_id
			)
		);
	//die($sql);
	$ok=$db->CompleteTrans();
	if(!$ok)
		error("There was a problem whilst adding the product, please try again.  If this problem persists please contact your designated support contact","Database Error");
?>