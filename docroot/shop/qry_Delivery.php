<?
	$results=$db->Execute(
		$sql = sprintf("
		(
			SELECT DISTINCT
				COUNT(shop_session_cart.id) count
			FROM
			(
				shop_session_cart
				,shop_products
			)
			WHERE
				shop_products.id=shop_session_cart.product_id
			AND
				shop_session_cart.session_id=%s
			AND
				shop_products.pick_up_only = 1
		)
		UNION ALL
		(
			SELECT DISTINCT
				COUNT(shop_session_cart.id) count
			FROM
			(
				shop_session_cart
				,shop_products
			)
			WHERE
				shop_products.id=shop_session_cart.product_id
			AND
				shop_session_cart.session_id=%s
		)
		"
			,$db->Quote($session->session_id)
			,$db->Quote($session->session_id)
		)
	);
	$count1 = $results->FetchRow();
	$count2 = $results->FetchRow();
	if($count1['count'] == $count2['count'])
	{
		header("location: ".$config['dir']."billing");
		exit;
	}
	
	if($user_session->check())
	{
		$delivery=$db->Execute(
			sprintf("
				SELECT
					shop_user_addresses.*
					,shop_countries.name country
				FROM
					shop_user_addresses
					,shop_countries
				WHERE
					shop_user_addresses.country_id = shop_countries.id
				AND
					shop_user_addresses.account_id=%u
				AND
					shop_user_addresses.type = 'delivery'
			"
				,$user_session->account_id
			)
		);
		$delivery = $delivery->GetRows();
	}
	else
		$delivery = array();
		
	$countries=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				shop_countries
			ORDER BY
				name
			ASC
		"
		)
	);
?>
