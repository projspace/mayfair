<?
	$result = $db->Execute(
		sprintf("
			SELECT
				COUNT(ssc.id) count
			FROM
				shop_session_cart ssc
			LEFT JOIN
				gift_list_items gli
			ON
				gli.id = ssc.gift_list_item_id
			AND
				gli.list_id = %u
			WHERE
				ssc.session_id=%s
			AND
				gli.id IS NULL
		"
			,$gift_list['id']
			,$db->Quote($session->session_id)
		)
	);
	$result = $result->FetchRow();
	if($result['count'])
	{
		header("Location: ".$config["dir"].'gift-registry/list/'.$_REQUEST['code'].'/confirmation');
		exit;
	}
?>