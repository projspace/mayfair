<?
	$results=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				shop_variables
			WHERE
				name IN ('gift_voucher_start','gift_voucher_increment_value','gift_voucher_increment_count','gift_voucher_increment_visible','packing','packing_visible')
		"
		)
	);
	while($row = $results->FetchRow())
		$$row['name'] = $row;
?>