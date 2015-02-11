<?
	$discount_code = $db->Execute(
		$sql = sprintf("
			SELECT
				*
			FROM
				shop_promotional_codes
			WHERE
				id = %u
		"
			,$_REQUEST['code_id']
		)
	);
	$discount_code = $discount_code->FetchRow();
?>
