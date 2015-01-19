<?
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();
	
	$product=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				shop_products
			WHERE
				id=%u
		"
			,$_POST['product_id']
		)
	);
	if($product->fields['parent_id']>0)
		$parent_id=$product->fields['parent_id'];
	else
		$parent_id=$_POST['product_id'];

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
			,$product->fields['category_id']
		)
	);

	$db->Execute(
		sprintf("
			INSERT INTO
				shop_products
			SET
				category_id = %u
				,brand_id = %u
				,parent_id = %u
				,name = %s
				,guid = %s
				,code = %s
				,meta_title = %s
				,meta_description = %s
				,meta_keywords = %s
				,price = %f
				,discount = %f
				,weight = %f
				,packing = %f
				,shipping = %s
				,description = %s
				,imagetype = %s
				,soldout = %u
				,stock = %u
				,`trigger` = %u
				,options = %s
				,optionslayout = %s
				,filename = %s
				,downloads = %u
				,custom = %s
				,ord = %u
		"
			,$product->fields['category_id']
			,$product->fields['brand_id']
			,$parent_id
			,$db->qstr($product->fields['name'])
			,$db->Quote(uniqid(rand(), true))
			,$db->qstr($product->fields['code'])
			,$db->qstr($product->fields['meta_title'])
			,$db->qstr($product->fields['meta_description'])
			,$db->qstr($product->fields['meta_keywords'])
			,$product->fields['price']
			,$product->fields['discount']
			,$product->fields['weight']
			,$product->fields['packing']
			,$product->fields['shipping']
			,$db->qstr($product->fields['description'])
			,$db->qstr($product->fields['imagetype'])
			,$product->fields['soldout']
			,$product->fields['stock']
			,$product->fields['trigger']
			,$db->qstr($product->fields['options'])
			,$db->qstr($product->fields['optionslayout'])
			,$db->qstr($product->fields['filename'])
			,$product->fields['downloads']
			,$db->qstr($product->fields['custom'])
			,$max->fields['max']+1
		)
	);
	$product_id=$db->Insert_ID();
	if($product_id)
	{
		$db->Execute(
			$sql=sprintf("
				UPDATE
					shop_products
				SET
					guid=%s
				WHERE
					id=%u
			"
				,$db->Quote(name2page($product->fields['guid']).$product_id)
				,$product_id
			)
		);
	}

	$ok=$db->CompleteTrans();
	if(!$ok)
    	error("There was a problem whilst copying the product, please try again.  If this persists please notify your designated support contact","Database Error");
?>
