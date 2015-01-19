<?
	$results=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				shop_variables
			WHERE
				name IN ('gift_days_advance','gift_name_min_length','gift_phone_min_digits','gift_pagination')
		"
		)
	);
	$gift_registry = array();
	while($row = $results->FetchRow())
		$gift_registry[str_replace('gift_', '', $row['name'])] = $row['value'];
?>