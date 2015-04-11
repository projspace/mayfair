<?
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();

	$db->Execute(
		sprintf("
			UPDATE
				shop_category_filters
			SET
				name = %s
				,type = %s
			WHERE
				id = %u
		"
			,$db->Quote(safe($_POST['name']))
			,$db->Quote(safe($_POST['type']))
			,$_REQUEST['filter_id']
		)
	);
	
	//values
	if(is_array($_REQUEST['saved_value']) && count($_REQUEST['saved_value']))
	{
		$db->Execute(
			sprintf("
				DELETE FROM
					shop_category_filter_items
				WHERE
					filter_id=%u
				AND
					id NOT IN (%s)
			"
				,$_REQUEST['filter_id']
				,implode(', ', $_REQUEST['saved_ids'])
			)
		);
		
		foreach($_REQUEST['saved_ids'] as $key=>$value_id)
			$db->Execute(
				sprintf("
					UPDATE
						shop_category_filter_items
					SET
						name = %s
						,ord = %u
					WHERE
						filter_id=%u
					AND
						id=%d
				"
					,$db->Quote($_REQUEST['saved_value'][$key])
					,$_REQUEST['saved_ord'][$key]
					,$_REQUEST['filter_id']
					,$value_id
				)
			);
	}
	else
		$db->Execute(
			sprintf("
				DELETE FROM
					shop_category_filter_items
				WHERE
					filter_id=%u
			"
				,$_REQUEST['filter_id']
			)
		);
		
	if(is_array($_REQUEST['value']) && count($_REQUEST['value']))
	{
		$format = array();
		$args = array();
		
		foreach($_REQUEST['value'] as $key=>$value)
		{
			$format[] = '(%u, %s, %u)';
			$args[] = $_REQUEST['filter_id'];
			$args[] = $db->Quote($value);
			$args[] = $_REQUEST['ord'][$key];
		}
		
		if(count($format))
		{
			$format = "INSERT INTO shop_category_filter_items (filter_id, name, ord) VALUES ".implode(',', $format);
			$db->Execute(vsprintf($format, $args));
		}
	}

	$ok=$db->CompleteTrans();
	if(!$ok)
		error("There was a problem whilst updating the filter, please try again.  If this problem persists please contact your designated support contact","Database Error");
?>