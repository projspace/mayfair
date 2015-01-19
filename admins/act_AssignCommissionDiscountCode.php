<?
	$code = $db->Execute(
		sprintf("
			SELECT
				id
				,shop_account_id
				,teacher_account_id
			FROM
				shop_promotional_codes
			WHERE
				shop_promotional_codes.id=%u
			AND
				shop_promotional_codes.deleted = 0
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
	/*if($code['shop_account_id']+$code['teacher_account_id'])
	{
		alert("The discount code has already been assigned to someone else.", "Stop");
		alertRender();
		return;
	}*/

	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();
	
	$db->Execute(
		sprintf("
			UPDATE
				shop_promotional_codes
			SET
				shop_account_id = %u
				,shop_commission = %f
				,teacher_account_id = %u
				,teacher_commission = %f
			WHERE
				id = %u
		"
			,$_POST['shop_account_id']
			,$_POST['shop_commission']
			,$_POST['teacher_account_id']
			,$_POST['teacher_commission']
			,$code['id']
		)
	);

	$ok=$db->CompleteTrans();
	if(!$ok)
		error("There was a problem whilst assigning the discount code, please try again.  If this problem persists please contact your designated support contact","Database Error");
?>