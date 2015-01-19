<?
	if($user_session->check())
	{
		$billing=$db->Execute(
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
					shop_user_addresses.type = 'billing'
			"
				,$user_session->account_id
			)
		);
		$billing = $billing->GetRows();
	}
	else
		$billing = array();
		
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
