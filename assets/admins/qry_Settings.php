<?
	$results=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				shop_variables
			WHERE
				name IN ('vat','from','meta_title','meta_keywords','meta_description','fb_code','fb_meta','google_category_id','postcode_search_distance','postcode_search_results','cron_orders_distance','cron_orders_commission','cron_orders_period','product_options')
		"
		)
	);
	$invoice = array();
	while($row = $results->FetchRow())
		$$row['name'] = $row;
?>