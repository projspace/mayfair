<?
	if($shippable)
	{
		/*$shipping = new WSShipping($config, $db);
		$services = $shipping->getServices(array(
			'delivery' => array(
				'name' => $session->session->fields['delivery_name']
				,'line' => $session->session->fields['delivery_line1']
				,'city' => $session->session->fields['delivery_line4']
				,'postcode' => $session->session->fields['delivery_postcode']
			)
			,'weight' => $vars['weight']
			,'total' => $vars['total']
		));*/
        $result=$db->Execute(
            $sql = sprintf("
                SELECT DISTINCT
                    shipping_options.*
                    ,(
                        SELECT
                            price
                        FROM
                            shipping_option_prices
                        WHERE
                            shipping_options.id = shipping_option_prices.option_id
                        AND
                            %f <= shipping_option_prices.value
                        ORDER BY
                            shipping_option_prices.value ASC
                        LIMIT 1
                    ) price
                FROM
                    shipping_options
                ORDER BY
                    price ASC
            "
                ,$vars['total']
            )
        );
        $services = array();
        while($row = $result->FetchRow())
            $services[$row['id']] = $row;
	}
	else
		$services = array();
?>