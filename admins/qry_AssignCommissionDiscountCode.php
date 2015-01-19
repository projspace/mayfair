<?
	$shops = $db->Execute(
		$sql = sprintf("
			SELECT
				*
			FROM
				shop_user_accounts
			WHERE
				shop = 1
			ORDER BY
				email ASC
		"
		)
	);
	
	$teachers = $db->Execute(
		$sql = sprintf("
			SELECT
				*
			FROM
				shop_user_accounts
			WHERE
				teacher = 1
			ORDER BY
				email ASC
		"
		)
	);
?>