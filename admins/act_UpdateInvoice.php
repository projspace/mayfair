<?
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();

	$vars = array('invoice_company'=>'s','invoice_address1'=>'s','invoice_address2'=>'s','invoice_address3'=>'s','invoice_address4'=>'s','invoice_phone'=>'s','invoice_fax'=>'s','invoice_email'=>'s','invoice_footer_left'=>'s','invoice_footer_right'=>'s');
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
		error("There was a problem whilst updating the invoice, please try again.  If this problem persists please contact your designated support contact","Database Error");
	else
	{
		$_SESSION['alert'] = 'Invoice updated.';
	}
?>