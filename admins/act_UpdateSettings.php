<?
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();

	$fields = array('vat'=>'f', 'from'=>'s', 'meta_title'=>'s', 'meta_keywords'=>'s', 'meta_description'=>'s', 'fb_code'=>'s', 'fb_meta'=>'s', 'google_category_id'=>'u', 'postcode_search_distance'=>'f', 'postcode_search_results'=>'u', 'cron_orders_distance'=>'f', 'cron_orders_commission'=>'f', 'cron_orders_period'=>'u', 'product_options'=>'s');
	
	foreach($fields as $field=>$type)
		$db->Execute(
			sprintf("
				UPDATE
					shop_variables
				SET
					value=%".$type."
				WHERE
					name = %s
			"
				,($type == 's')?$db->Quote($_POST[$field]):$_POST[$field]
				,$db->Quote($field)
			)
		);
	
	$ok=$db->CompleteTrans();
	if(!$ok)
		error("There was a problem whilst updating the configuration settings, please try again.  If this problem persists please contact your designated support contact","Database Error");
	else
	{
		$_SESSION['alert'] = 'Update successful.';
	}
?>