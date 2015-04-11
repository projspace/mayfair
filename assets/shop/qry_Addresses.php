<?
	if($logged_in = $user_session->check())
	{
		$account=$db->Execute(
			sprintf("
				SELECT
					*
				FROM
					shop_user_accounts
				WHERE
					id = %u
			"
				,$user_session->account_id
			)
		);
		$account = $account->FetchRow();
		
		$results=$db->Execute(
			sprintf("
				SELECT
					*
				FROM
					shop_user_addresses
				WHERE
					account_id=%u
			"
				,$user_session->account_id
			)
		);
		$billing_count = 0;
		$delivery_count = 0;
		$addresses = array();
		while($row = $results->FetchRow())
		{
			if($row['billing'])
				$billing_count++;
			if($row['delivery'] && $row['country_id'] == $session->session->fields['delivery_country_id']+0)
				$delivery_count++;
			$addresses[] = $row;
		}
	}
	else
	{
		$account = false;
		$billing_count = 0;
		$delivery_count = 0;
		$addresses = array();
	}
	
	$billing = array();
	if(trim($session->session->fields['billing_name']) == '' && $logged_in)
		$billing['name'] = trim($account['firstname'].' '.$account['lastname']);
	else
		$billing['name'] = $session->session->fields['billing_name'];
	
	if(trim($session->session->fields['billing_email']) == '' && $logged_in)
		$billing['email'] = $account['email'];
	else
		$billing['email'] = $session->session->fields['billing_email'];
	
	if(trim($session->session->fields['billing_phone']) == '' && $logged_in)
		$billing['phone'] = $account['phone'];
	else
		$billing['phone'] = $session->session->fields['billing_phone'];
	
	if(trim($session->session->fields['billing_address']) == '' && $logged_in)
	{
		foreach($addresses as $row)
			if($row['billing'])
			{
				$billing['address'] = $row['line1'];
				break;
			}
	}
	else
	{
		$billing['line1'] = $session->session->fields['billing_line1'];
		$billing['line2'] = $session->session->fields['billing_line2'];
		$billing['line3'] = $session->session->fields['billing_line3'];
		$billing['line4'] = $session->session->fields['billing_line4'];
		$billing['postcode'] = $session->session->fields['billing_postcode'];
	}
		
	$delivery = array();
	if(trim($session->session->fields['delivery_name']) == '' && $logged_in)
		$delivery['name'] = trim($account['firstname'].' '.$account['lastname']);
	else
		$delivery['name'] = $session->session->fields['delivery_name'];
	
	if(trim($session->session->fields['delivery_email']) == '' && $logged_in)
		$delivery['email'] = $account['email'];
	else
		$delivery['email'] = $session->session->fields['delivery_email'];
	
	if(trim($session->session->fields['delivery_phone']) == '' && $logged_in)
		$delivery['phone'] = $account['phone'];
	else
		$delivery['phone'] = $session->session->fields['delivery_phone'];
	
	if(trim($session->session->fields['delivery_address']) == '' && $logged_in)
	{
		foreach($addresses as $row)
			if($row['delivery'])
			{
				$delivery['address'] = $row['line1'];
				break;
			}
	}
	else
	{
		$delivery['line1'] = $session->session->fields['delivery_line1'];
		$delivery['line2'] = $session->session->fields['delivery_line2'];
		$delivery['line3'] = $session->session->fields['delivery_line3'];
		$delivery['line4'] = $session->session->fields['delivery_line4'];
		$delivery['postcode'] = $session->session->fields['delivery_postcode'];
	}
?>
