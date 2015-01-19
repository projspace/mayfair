<?
	if(!$_REQUEST['id'])
		$_REQUEST['id']=1;

	$category=$db->Execute(
		sprintf("
			SELECT 
				* 
			FROM 
				shop_categories 
			WHERE 
				id = %u
		"
			,$_REQUEST['id']
		)
	);
	$keys=$category->GetKeys();
	$row=$category->FetchRow();
	$temp=explode("\n",trim($row[$keys['trail']]));
	for($i=0;$i<count($temp);$i++)
	{
		$histtemp=explode(":",$temp[$i]);
		$history[$i]['name']=$histtemp[0];
		$history[$i]['id']=$histtemp[1];
	}

	$children=$db->Execute(
		sprintf("
			SELECT 
				name
				,id 
			FROM 
				shop_categories 
			WHERE 
				parent_id = %u
			ORDER BY 
				name ASC
		"
			,$_REQUEST['id']
		)
	);

	$products=$db->Execute(
		sprintf("
			SELECT 
				* 
			FROM 
				shop_products 
			WHERE 
				category_id = %u
			AND 
				id > 1 
			ORDER BY 
				name ASC
		"
			,$_REQUEST['id']
		)
	);
?>