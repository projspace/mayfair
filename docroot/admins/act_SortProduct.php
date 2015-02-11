<?
	$table = 'shop_products';
	$post_var = 'item_id';
	$post_steps = 'steps';
	$second_table_var = 'category_id';
	$second_post_var = 'category_id';
	$second_post_var_type = 'int';
	
	if($second_post_var_type == 'string')
		$second_var = $db->Quote($_REQUEST[$second_post_var]);
	else
		$second_var = $_REQUEST[$second_post_var];
	
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();

	//Get current ord and note the min ord is 1

	$ord=$db->Execute(
		sprintf("
			SELECT 
				t.id
				,t.ord
			FROM `%s` t
			WHERE	t.ord %s (SELECT t2.ord FROM `%s` t2 WHERE t2.id = %u AND `%s`=%s) AND `%s`=%s
			ORDER BY t.ord %s
			LIMIT %u
		"
			,$table
			,($_REQUEST[$post_steps]+0 > 0)?'>=':'<='
			,$table
			,$_REQUEST[$post_var]
			,$second_table_var
			,$second_var
			,$second_table_var
			,$second_var
			,($_REQUEST[$post_steps]+0 > 0)?'ASC':'DESC'
			,abs($_REQUEST[$post_steps]+0)+1
		)
	);
	if($ord->RecordCount() == abs($_REQUEST[$post_steps]+0)+1)
	{
		$order = array();
		while($row = $ord->FetchRow())
			$order[$row['id']] = $row['ord'];
		
		if($_REQUEST[$post_steps]+0 > 0)
		{
			$reorder = array();
			foreach($order as $item_id=>$ord)
				$reorder[$item_id] = null;
				
			$last = end($order);
			reset($order);
			$index = 0;
			foreach($reorder as $item_id=>$ord)
			{
				if($index == 0)
					$reorder[$item_id] = $last;
				else
				{
					$reorder[$item_id] = current($order);
					next($order);
				}
				$index++;
			}
		}
		else
		{
			$order = array_reverse($order, true);
			$reorder = array();
			foreach($order as $item_id=>$ord)
				$reorder[$item_id] = null;
				
			$first = reset($order);
			$index = 0;
			foreach($reorder as $item_id=>$ord)
			{
				if($index == count($reorder)-1)
					$reorder[$item_id] = $first;
				else
					$reorder[$item_id] = next($order);
				$index++;
			}
		}
		
		foreach($reorder as $item_id=>$ord)
			$db->Execute(
				sprintf("
					UPDATE
						`%s` t
					SET
						t.ord = %u
					WHERE
						t.id = %u
				"
					,$table
					,$ord
					,$item_id
				)
			);
	}
	else
		$db->FailTrans();

	$ok=$db->CompleteTrans();
?>