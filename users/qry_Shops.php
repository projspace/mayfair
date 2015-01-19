<?php 

	$shops = $db->Execute(
		sprintf("
			SELECT
				*
			FROM
				shop_user_shops
			AND
				hidden = 0
		")
	);