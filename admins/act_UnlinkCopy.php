<?
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();
	
	$details=$db->Execute(
		sprintf("
			SELECT
				imagetype
				,parent_id
			FROM
				shop_products
			WHERE
				id=%u
		"
			,$_POST['product_id']
		)
	);

	$row=$details->FetchRow();

	$details=$db->Execute(
		sprintf("
			SELECT
				id
				,color_id
				,imagetype
			FROM
				shop_product_images
			WHERE
				product_id=%u
		"
			,$row['parent_id']
		)
	);

	while($row=$details->FetchRow())
	{
		$db->Execute(
			sprintf("
				INSERT INTO
					shop_product_images
					(
						product_id
						,color_id
						,imagetype
					)
				VALUES
					(
						%u
						,%s
						,%s
					)
			"
				,$_POST['product_id']
				,($row['color_id']+0)?$row['color_id']:'NULL'
				,$db->Quote($row['imagetype'])
			)
		);
		$imageid=$db->Insert_ID();

		foreach($config['size']['product'] as $type=>$size)
		{
			if($type != 'image')
			{
				$src_file = $config['path'].'images/product/'.$type.'/'.$row['id'].'.'.$row['imagetype'];
				$dest_file = $config['path'].'images/product/'.$type.'/'.$imageid.'.'.$row['imagetype'];
			}
			else
			{
				$src_file = $config['path'].'images/product/'.$row['id'].'.'.$row['imagetype'];
				$dest_file = $config['path'].'images/product/'.$imageid.'.'.$row['imagetype'];
			}
			
			copy($src_file, $dest_file);
		}
	}
	
	$options=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				shop_product_options
			WHERE
				product_id=%u
		"
			,$row['parent_id']
		)
	);

	while($row=$options->FetchRow())
	{
		$db->Execute(
			sprintf("
				INSERT INTO
					shop_product_options
				SET
					product_id = %u
					,upc_code = %s
					,size_id = %u
					,width_id = %s
					,color_id = %u
					,quantity = %u
					,price = %f
			"
				,$_POST['product_id']
				,$db->Quote($row['upc_code'].' DUPLICATE '.uniqid(rand(), true))
				,$row['size_id']
				,($row['width_id']+0)?$row['width_id']:'NULL'
				,$row['color_id']
				,$row['quantity']
				,$row['price']
			)
		);
	}

	$db->Execute(
		sprintf("
			UPDATE
				shop_products
			SET
				parent_id=0
			WHERE
				id=%u
		"
			,$_POST['product_id']
		)
	);
	
	$ok=$db->CompleteTrans();
	if(!$ok)
    	error("There was a problem whilst unlinking the product copy, please try again.  If this persists please notify your designated support contact","Database Error");
?>
