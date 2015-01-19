<?
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();

	$db->Execute(
		sprintf("
			INSERT INTO
				shop_category_boxes
			SET
				category_id = %u
				,type = %s
				,ord = IFNULL((SELECT MAX(scb.ord)+1 FROM shop_category_boxes scb), 1)
		"
			,$_REQUEST['category_id']
			,$db->Quote(safe($_POST['type']))
		)
	);
	$box_id=$db->Insert_ID();
	
	if($box_id)
	{
		$sql_insert = array();
		switch($_POST['type'])
		{
			case 'big_small':
				$sql_insert[] = sprintf("(%u, %s)", $box_id, $db->Quote('big1'));
				$sql_insert[] = sprintf("(%u, %s)", $box_id, $db->Quote('small'));
				break;
			case 'small_big':
				$sql_insert[] = sprintf("(%u, %s)", $box_id, $db->Quote('small'));
				$sql_insert[] = sprintf("(%u, %s)", $box_id, $db->Quote('big1'));
				break;
			case 'big_2_small':
				$sql_insert[] = sprintf("(%u, %s)", $box_id, $db->Quote('big2'));
				$sql_insert[] = sprintf("(%u, %s)", $box_id, $db->Quote('small1'));
				$sql_insert[] = sprintf("(%u, %s)", $box_id, $db->Quote('small2'));
				break;
			case '2_small_big':
				$sql_insert[] = sprintf("(%u, %s)", $box_id, $db->Quote('small1'));
				$sql_insert[] = sprintf("(%u, %s)", $box_id, $db->Quote('small2'));
				$sql_insert[] = sprintf("(%u, %s)", $box_id, $db->Quote('big2'));
				break;
			default:
				break;
		}
		if(count($sql_insert))
		{
			$db->Execute(
				sprintf("
					INSERT INTO
						shop_category_box_items
					(
						box_id
						,type
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
		error("There was a problem whilst adding the box, please try again.  If this problem persists please contact your designated support contact","Database Error");
?>