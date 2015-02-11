<?
	$item=$db->Execute(
		sprintf("
			SELECT
				gli.*
				,SUM(sop.quantity) bought
				,spo.quantity stock
			FROM
				gift_list_items gli
			JOIN
				gift_lists
			ON
				gift_lists.id = gli.list_id
			LEFT JOIN
				shop_order_products sop
			ON
				sop.gift_list_item_id = gli.id
			JOIN
				shop_product_options spo
			ON
				spo.id = gli.option_id
			AND
				spo.product_id = gli.product_id
			WHERE
				gift_lists.account_id = %u
			AND
				gift_lists.id = %u
			AND
				gli.id = %u
			GROUP BY
				gli.id
		"
			,$user_session->account_id
			,$_REQUEST['list_id']
			,$_REQUEST['item_id']
		)
	);
	$item = $item->FetchRow();
?>