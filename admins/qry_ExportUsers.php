<?
	$users=$db->Execute(
		$sql = sprintf("
			SELECT DISTINCT
				*
			FROM
				shop_user_accounts
			ORDER BY
				email ASC
		"
		)
	);
?>