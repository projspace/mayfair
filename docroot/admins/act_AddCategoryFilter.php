<?
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();

	$db->Execute(
		sprintf("
			INSERT INTO
				shop_category_filters
			SET
				category_id = %u
				,name = %s
				,type = %s
				,ord = IFNULL((SELECT MAX(scf.ord)+1 FROM shop_category_filters scf WHERE category_id = %u), 1)
		"
			,$_REQUEST['category_id']
			,$db->Quote(safe($_POST['name']))
			,$db->Quote(safe($_POST['type']))
			,$_REQUEST['category_id']
		)
	);
	$filter_id=$db->Insert_ID();
	
	if($filter_id)
	{
		$sql_insert = array();
		$ord = 1;
		foreach($_POST['value'] as $value)
		{
			$sql_insert[] = sprintf("(%u, %s, %u)", $filter_id, $db->Quote(safe($value)), $ord);
			$ord++;
		}
			
		if(count($sql_insert))
		{
			$db->Execute(
				sprintf("
					INSERT INTO
						shop_category_filter_items
					(
						filter_id
						,name
						,ord
					)
					VALUES
						%s
				"
					,implode(',', $sql_insert)
				)
			);
		}
	}
	
	$ok=$db->CompleteTrans();
	if(!$ok)
		error("There was a problem whilst adding the filter, please try again.  If this problem persists please contact your designated support contact","Database Error");
?>