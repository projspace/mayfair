<?
	$user=$db->Execute(
		sprintf("
			SELECT
				shop_user_accounts.*
			FROM
				shop_user_accounts
			WHERE
				shop_user_accounts.id=%u
		"
			,$_REQUEST['user_id']
		)
	);
	$user = $user->FetchRow();
	
	if ( $user['teacher'] == 1 ) {
		$teacher = $db->Execute(sprintf("SELECT * FROM shop_user_teachers WHERE user_id = %u",$_REQUEST['user_id']));
		$teacher = $teacher->FetchRow();
	}
	
	if ( $user['shop'] == 1 ) {
		$shop = $db->Execute(sprintf("SELECT * FROM shop_user_shops WHERE user_id = %u AND hidden = 0",$_REQUEST['user_id']));
		$shop = $shop->FetchRow();
	}
	
	$shipping=$db->Execute(
		sprintf("
			SELECT
				shop_user_addresses.*
				,shop_countries.name country
			FROM
				shop_user_addresses
			LEFT JOIN
				shop_countries
			ON
				shop_countries.id = shop_user_addresses.country_id
			WHERE
				shop_user_addresses.type = 'delivery'
			AND
				shop_user_addresses.account_id = %u
		"
			,$_REQUEST['user_id']
		)
	);
	
	$billing=$db->Execute(
		sprintf("
			SELECT
				shop_user_addresses.*
				,shop_countries.name country
			FROM
				shop_user_addresses
			LEFT JOIN
				shop_countries
			ON
				shop_countries.id = shop_user_addresses.country_id
			WHERE
				shop_user_addresses.type = 'billing'
			AND
				shop_user_addresses.account_id = %u
		"
			,$_REQUEST['user_id']
		)
	);
	
	$discount_codes = $db->Execute(
		$sql = sprintf("
			SELECT
				shop_promotional_codes.*
				,COUNT(DISTINCT supc1.id) assigned
				,COUNT(DISTINCT supc2.id) used
			FROM
			(
				shop_promotional_codes
				,shop_user_promotional_codes
			)
			LEFT JOIN
				shop_user_promotional_codes supc1
			ON
				supc1.code_id = shop_promotional_codes.id
			AND
				supc1.account_id = %u
			AND
				supc1.order_id = 0
			LEFT JOIN
				shop_user_promotional_codes supc2
			ON
				supc2.code_id = shop_promotional_codes.id
			AND
				supc2.account_id = %u
			AND
				supc2.order_id != 0
			WHERE
				shop_promotional_codes.id = shop_user_promotional_codes.code_id
			AND
				shop_promotional_codes.deleted = 0
			AND
				shop_user_promotional_codes.account_id = %u
			GROUP BY
				shop_promotional_codes.id
		"
			,$_REQUEST['user_id']
			,$_REQUEST['user_id']
			,$_REQUEST['user_id']
		)
	);
?>