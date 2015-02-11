<?
	$address=$db->Execute(
		sprintf("
			SELECT
				sua.*
				,shop_countries.area_id
			FROM
				shop_user_addresses sua
			LEFT JOIN
				shop_countries
			ON
				shop_countries.id = sua.country_id
			WHERE
				sua.account_id = %u
			AND
				sua.type = 'delivery'
			AND
				sua.delivery = 1
		"
			,$user_session->account_id
		)
	);
	$address = $address->FetchRow();
?>