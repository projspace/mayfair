<?
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();

	$vars = array('gift_voucher_start'=>'f','gift_voucher_increment_value'=>'f','gift_voucher_increment_count'=>'u','gift_voucher_increment_visible'=>'u','packing'=>'f','packing_visible'=>'u');
	foreach($vars as $var=>$type)
		$db->Execute(
			sprintf("
				UPDATE
					shop_variables
				SET
					value=%".$type."
				WHERE
					name = %s
			"
				,$_POST[$var]
				,$db->Quote($var)
			)
		);

	$ok=$db->CompleteTrans();
	if(!$ok)
		error("There was a problem whilst updating the basket, please try again.  If this problem persists please contact your designated support contact","Database Error");
	else
	{
		$_SESSION['alert'] = 'Basket updated.';
	}
?>