<?
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();
	
	if(isset($_POST['ref']))
	{
		$query="DELETE FROM
					shop_refs
				WHERE\n";
		$count=0;
		$num=count($_POST['ref']);
		for($i=0;$i<$num;$i++)
		{
			if($i>0)
				$query.="OR ";
			$query.="id=%u\n";
		}
		$db->Execute(vsprintf($query,$_POST['ref']));
	}
	if(isset($_POST['product']))
	{
		$query=sprintf("UPDATE
				shop_products
			SET
				category_id=%u
			WHERE\n",$category_id);
		$count=0;
		$num=count($_POST['product']);
		for($i=0;$i<$num;$i++)
		{
			if($i>0)
				$query.="OR ";
			$query.="id=%u\n";
		}
		$db->Execute(vsprintf($query,$_POST['product']));
	}
	//Defragment order values
	$products=$db->Execute(
		sprintf("
			SELECT
				id
			FROM
				shop_products
			WHERE
				category_id=%u
			ORDER BY
				ord ASC
		"
			,$_POST['category_id']
		)
	);
	$count=0;
	while($row=$products->FetchRow())
	{
		$db->Execute(
			sprintf("
				UPDATE
					shop_products
				SET
					ord=%u
				WHERE
					id=%u
			"
				,$count
				,$row['id']
			)
		);
		$count++;
	}
	
	$ok=$db->CompleteTrans();
	if(!$ok)
    	error("There was a problem whilst mass deleting the products, please try again.  If this persists please notify your designated support contact","Database Error");
?>
