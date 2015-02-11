<?
	$gift_list=$db->Execute(
		sprintf("
			SELECT
				gl.*
				,CONCAT_WS(' ', sua.firstname, sua.middlename, sua.lastname) account
				,addr.name delivery_name
				,addr.email delivery_email
				,addr.phone delivery_phone
				,addr.line1 delivery_line1
				,addr.line2 delivery_line2
				,addr.line3 delivery_line3
				,addr.line4 delivery_line4
				,addr.postcode delivery_postcode
				,addr.country_id delivery_country_id
			FROM
				gift_lists gl
            LEFT JOIN
                shop_user_accounts sua
            ON
                gl.account_id = sua.id
            LEFT JOIN
                shop_user_addresses addr
            ON
                addr.account_id = sua.id
            AND
                addr.id = gl.delivery_address_id
            WHERE
                gl.id = %u
		"
		    ,$_REQUEST['list_id']
        )
	);
	$gift_list = $gift_list->FetchRow();
?>