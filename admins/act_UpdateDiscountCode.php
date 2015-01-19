<?
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();

	$db->Execute(
		$sql = sprintf("
			UPDATE
				shop_promotional_codes
			SET
				expiry_date = %s
				,min_order = %f
				,use_count = %u
				,all_users = %u
				,gift_list_id = %u
			WHERE
				id = %u
		"
			,$db->Quote(implode('-', array_reverse(explode('/', trim($_POST['expiry_date'])))))
			,$_POST['min_order']
			,$_POST['use_count']
			,$_POST['all_users']?1:0
			,$_POST['gift_list_id']
			,$_REQUEST['code_id']
		)
	);
	
	$ok=$db->CompleteTrans();
	if(!$ok)
		error("There was a problem whilst updating the discount code, please try again.  If this problem persists please contact your designated support contact","Database Error");
?>