<?
	/**
	 * e-Commerce System Data Feed/Export Plugin
	 * Copyright (c) 2002-2006 Philip John, All Rights Reserved.
	 * Author	: Philip John
	 * Version	: 6.0
	 *
	 * PROPRIETARY/CONFIDENTIAL.  Use is subject to license terms.
	 */
?>
<?
	$db->Execute(
		$sql=sprintf("
			UPDATE
				shop_sessions
			SET
				nitems=%s
				,total=%f
				,weight=%f
				,packing=%s
				,shipping=%f
				,tax=%f
				,multibuy_discount=%f
				,discount_code_id=%u
				,promotional_discount=%f
				,promotional_discount_type=%s
				,account_id=%u
			WHERE
				session_id=%s
		"
			,$vars['nitems']
			,$vars['total']
			,$vars['weight']
			,($vars['packing'] !== null)?($vars['packing']+0):'NULL'
			,$vars['shipping']
			,$vars['tax']
			,$vars['multibuy_discount']
			,$vars['discount_code_id']
			,$vars['promotional_discount']
			,$db->Quote($vars['promotional_discount_type'])
			,$user_session->account_id
			,$db->Quote($session->session_id)
		)
	);
	
	if($session->session->fields['discount_code'] != '')
	{
		$results=$db->Execute(
			$sql = sprintf("
			(
				SELECT
					shop_promotional_codes.*
				FROM
				(
					shop_promotional_codes
					,shop_user_promotional_codes
				)
				LEFT JOIN
					shop_user_promotional_codes supc
				ON
					supc.code_id = shop_promotional_codes.id
				AND
					supc.account_id = %u
				AND
					supc.order_id != 0
				WHERE
					shop_promotional_codes.code = %s
				AND
					shop_promotional_codes.deleted = 0
				AND
					shop_promotional_codes.suspended = 0
				AND
					IF(shop_promotional_codes.expiry_date, CURDATE() < shop_promotional_codes.expiry_date, 1)
				AND
					shop_user_promotional_codes.code_id = shop_promotional_codes.id
				AND
					shop_user_promotional_codes.account_id = %u
				AND
					shop_user_promotional_codes.order_id = 0
				AND
					IF(shop_promotional_codes.value_type = 'percent', %f, %f) >= shop_promotional_codes.min_order
				AND
					IF(shop_promotional_codes.gift_list_id > 0, shop_promotional_codes.gift_list_id = %u, 1)
				GROUP BY
					shop_promotional_codes.id
				HAVING
					COUNT(DISTINCT supc.order_id) < shop_promotional_codes.use_count
			)
			UNION ALL
			(
				SELECT
					shop_promotional_codes.*
				FROM
					shop_promotional_codes
				LEFT JOIN
					shop_user_promotional_codes supc
				ON
					supc.code_id = shop_promotional_codes.id
				AND
					supc.account_id = %u
				AND
					supc.order_id != 0
				WHERE
					shop_promotional_codes.code = %s
				AND
					shop_promotional_codes.deleted = 0
				AND
					shop_promotional_codes.suspended = 0
				AND
					shop_promotional_codes.all_users = 1
				AND
					IF(shop_promotional_codes.expiry_date, CURDATE() < shop_promotional_codes.expiry_date, 1)
				AND
					IF(shop_promotional_codes.value_type = 'percent', %f, %f) >= shop_promotional_codes.min_order
				AND
					IF(shop_promotional_codes.gift_list_id > 0, shop_promotional_codes.gift_list_id = %u, 1)
				GROUP BY
					shop_promotional_codes.id
				HAVING
					COUNT(DISTINCT supc.order_id) < shop_promotional_codes.use_count
			)
			"
				,$user_session->account_id
				,$db->Quote($session->session->fields['discount_code'])
				,$user_session->account_id
				,$vars['total_discount_percentage']
				,$vars['total']
				,$session->session->fields['last_gift_list_id']
				,$user_session->account_id
				,$db->Quote($session->session->fields['discount_code'])
				,$vars['total_discount_percentage']
				,$vars['total']
				,$session->session->fields['last_gift_list_id']
			)
		);
		if($row = $results->FetchRow())
		{
			if($vars['promotional_discount']+0 && $row['value_type'] == 'percent')
				foreach($rows as $prod)
					$db->Execute(
						sprintf("
							UPDATE
								shop_session_cart
							SET
								promotional_discount=%f
							WHERE
								id=%u
						"
							,(!$prod['exclude_discounts'])?($row['value'] * (($prod['total']+0)/100)):0
							,$prod['cart_id']
						)
					);
					
			if($row['all_users'])
			{
				$count = $db->Execute(
					sprintf("
						SELECT
							COUNT(shop_user_promotional_codes.id) count
						FROM
							shop_user_promotional_codes
						WHERE
							shop_user_promotional_codes.code_id = %u
					"
						,$row['id']
					)
				);
				$count = $count->FetchRow();
				$count = $count['count']+0;
				if(!$count)
				{
					$db->Execute(
						$sql = sprintf("
							INSERT INTO
								shop_user_promotional_codes
							SET
								account_id = %u
								,code_id = %u
						"
							,$user_session->account_id
							,$row['id']
						)
					);
				}
			}
		}
	}
?>