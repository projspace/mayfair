<?
    if($session->session->fields['last_gift_list_id']+0)
    {
        $gift_address=$db->Execute(
            sprintf("
                SELECT
                    shop_user_addresses.*
                FROM
                    gift_lists
                JOIN
                    shop_user_addresses
                ON
                    gift_lists.delivery_address_id = shop_user_addresses.id
                WHERE
                    gift_lists.id = %u
            "
                ,$session->session->fields['last_gift_list_id']
            )
        );
        $gift_address = $gift_address->FetchRow();

        $address = array(
            'delivery_line1' => $gift_address['line1']
            ,'delivery_line4' => $gift_address['line4']
            ,'delivery_postcode' => $gift_address['postcode']
            ,'delivery_country_id' => $gift_address['country_id']
        );
    }
    else
        $address = array(
            'delivery_line1' => $session->session->fields['delivery_line1']
            ,'delivery_line4' => $session->session->fields['delivery_line4']
            ,'delivery_postcode' => $session->session->fields['delivery_postcode']
            ,'delivery_country_id' => $session->session->fields['delivery_country_id']
        );

	if(in_array($address['delivery_country_id']+0, array(5))) //delivery in California
	{
		$ws_tax = new WSTax($config);
		$prods = array();
		foreach($rows as $row)
			$prods[] = array(
				'id' => $row['id']
				//,'sales_amount' => $row['total']
				,'sales_amount' => ($row['cart_price'] - $row['promotional_discount']) * $row['cart_quantity']
				,'unit_price' => $row['cart_price'] - $row['promotional_discount']
				,'upc_code' => $row['upc_code']
				,'quantity' => $row['cart_quantity']
			);
		
		$tax_delivery = array(
			'street' => $address['delivery_line1']
			,'city' => $address['delivery_line4']
			,'postcode' => $address['delivery_postcode']
			,'country' => $vars['country']
		);
		
		$tax_billing = array(
			'name' => $session->session->fields['billing_name']
		);
			
		$vars['tax'] = $ws_tax->getTax(array('time'=>time()),$prods,$tax_delivery,$tax_billing);
	}
	else
		$vars['tax'] = 0;
?>
