<?
	$code = $db->Execute(
		sprintf("
			SELECT
				shop_promotional_codes.*
				,COUNT(shop_user_promotional_codes.id) assigned
			FROM
				shop_promotional_codes
			LEFT JOIN
				shop_user_promotional_codes
			ON
				shop_user_promotional_codes.code_id = shop_promotional_codes.id
			WHERE
				shop_promotional_codes.id=%u
			AND
				shop_promotional_codes.deleted = 0
			GROUP BY
				shop_promotional_codes.id
		"
			,$_REQUEST['code_id']
		)
	);
	$code = $code->FetchRow();
	if(!$code)
	{
		alert("The discount code does not exist.", "Stop");
		alertRender();
		return;
	}
	if($code['assigned'])
	{
		alert("The discount code has already been assigned to someone else.", "Stop");
		alertRender();
		return;
	}

	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();
	
	$sql_insert = array();
	foreach($_REQUEST['account_ids'] as $account_id)
		for($i=0;$i<$code['use_count'];$i++)
			$sql_insert[] = sprintf("(%u, %u)", $account_id, $_REQUEST['code_id']);
		
	if(count($sql_insert))
	{
		$db->Execute(
			sprintf("
				INSERT INTO
					shop_user_promotional_codes
				(
					account_id
					,code_id
				)
				VALUES
					%s
			"
				,implode(',', $sql_insert)
			)
		);
	}

	$ok=$db->CompleteTrans();
	if(!$ok)
		error("There was a problem whilst assigning the discount code, please try again.  If this problem persists please contact your designated support contact","Database Error");
?>