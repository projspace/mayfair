<?
	$validator=new Validation("summary");
	$validator->addRequired("billing_name","Name");
	$validator->addRequired("billing_email","Email");
	$validator->addRequired("billing_phone","Phone");
	$validator->addRequired("billing_line1","Address Line 1");
	$validator->addRequired("billing_postcode","Postcode");
	$validator->addRequired("cvv","Card Code");
	//$validator->addRequired("billing_country_id","State");
	$validator->addRegex("billing_email","Email","^[a-z0-9._-]+@[a-z0-9._-]+\.[a-z]+$","Please enter a valid email address.","main","i");

    if(!$session->session->fields['last_gift_list_id']+0)
	{
        $validator->addRequired("delivery_name","Name");
        $validator->addRequired("delivery_email","Email");
        $validator->addRequired("delivery_phone","Phone");
        $validator->addRequired("delivery_line1","Address Line 1");
        $validator->addRequired("delivery_line4","Address Line 4");
        $validator->addRequired("delivery_postcode","Postcode");
        $validator->addRequired("delivery_country_id","State");
        $validator->addRegex("delivery_email","Email","^[a-z0-9._-]+@[a-z0-9._-]+\.[a-z]+$","Please enter a valid email address.","main","i");

        $validator->addCustom("delivery_postcode","Postcode","checkSpeedtaxAddress","The delivery address is incomplete. Please check your details and try again.");
    }

	function val_checkSpeedtaxAddress($value)
	{
		global $db, $config;
		
		include_once '../lib/lib_WSTax.php';
		
		$state = $db->Execute(
			sprintf("
				SELECT
					name
				FROM
					shop_countries
				WHERE
					id = %u
			"
				,$_POST['delivery_country_id']
			)
		);
		$state = $state->FetchRow();
		if(!in_array($state['id']+0, array(23, 27))) //delivery in Nevada or New York
			return true;
		
		$addr = array();
		if(($line = trim($_POST['delivery_line1'])) != '')
			$addr[] = $line;
		if(($line = trim($_POST['delivery_line2'])) != '')
			$addr[] = $line;
		if(($line = trim($_POST['delivery_line3'])) != '')
			$addr[] = $line;
		$line1 = implode(', ', $addr);
		
		$addr = array();
		if(($line = trim($_POST['delivery_line4'])) != '')
			$addr[] = $line;
		if(($line = trim($state['name'])) != '')
			$addr[] = $line;
		if(($line = trim($_POST['delivery_postcode'])) != '')
			$addr[] = $line;
		$line2 = implode(', ', $addr);
			
		$tax = new WSTax($config);
		return $tax->checkAddress($line1, $line2);
	}
?>