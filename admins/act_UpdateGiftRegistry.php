<?
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();

	$vars = array('gift_days_advance'=>'u','gift_name_min_length'=>'u','gift_phone_min_digits'=>'u','gift_pagination'=>'u');
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
				,($type == 's')?$db->Quote($_POST[$var]):$_POST[$var]
				,$db->Quote($var)
			)
		);

	$ok=$db->CompleteTrans();
	if(!$ok)
		error("There was a problem whilst updating the gift registry, please try again.  If this problem persists please contact your designated support contact","Database Error");
	else
	{
		$_SESSION['alert'] = 'Gift registry settings updated.';
	}
?>