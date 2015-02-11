<?
	$results=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				shop_variables
			WHERE
				name IN ('invoice_company','invoice_address1','invoice_address2','invoice_address3','invoice_address4','invoice_phone','invoice_fax','invoice_email','invoice_footer_left','invoice_footer_right')
		"
		)
	);
	$invoice = array();
	while($row = $results->FetchRow())
		$invoice[str_replace('invoice_', '', $row['name'])] = $row['value'];
?>