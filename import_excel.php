<?
$row = 1;
$account = array();

die;
if (($handle = fopen("customer.csv", "r")) !== FALSE) {
	while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
		$num = count($data);
		
		if($row != 1){
			
				for ($c=0; $c < $num; $c++) {
					//echo $data[$c]." ";
					
					$account[$row]['name'] = $data[0];
					$account[$row]['address1'] = $data[1];
					$account[$row]['address2'] = $data[2];
					$account[$row]['city'] = $data[3];
					$account[$row]['country'] = $data[5];
					$account[$row]['zip'] = $data[6];
					$account[$row]['phone'] = $data[7];
				}
			
		}
		$row++;
	}
	fclose($handle);
}

include ("lib/lib_Payment.php");
include ("lib/payment/cfg_Authorize.php");
include ("lib/payment/lib_Authorize.php");

//$account[2]['name'] = "BODY'P,HL O";
//from here starts the account loop
foreach($account as $data){
	
	$email = str_replace("'","",$data['name']);
	$email = str_replace(",","",$email);
	$email = str_replace("-","",$email);
	$email = str_replace(" ","",$email);
	$email .= "@gmail.com";

	if( $data['name'] != "") {
		$db->Execute(
			sprintf("
					INSERT
						shop_user_accounts
					SET
						email = %s
						,password = %s
						,dob = %s
				"
				,$db->Quote($email)
				,$db->Quote('password')
				,$db->Quote(implode('-', array_reverse(explode('/', $_POST['dob']))))
			)
		);
		$user_id=$db->Insert_ID();
	}
	
	if ( $user_id && $config['psp']['driver'] == 'Authorize' ) {
	
		$psp = new Authorize($config,$smarty,$db);
	
		if ( $authorize_profile_id = $psp->CreateCustomerProfile($user_id,$email))
			include ("users/act_UpdateAuthorizeProfileId.php");
	}
	
	if( $user_id && $data['name'] != "") {
		if($dates = get_google_coords($data['address1']." ".$data['address2']." ".$data['city']." ".$data['zip']))
		{
			$lat = $dates['lat']+0;
			$long = $dates['long']+0;
		}
		
		$db->Execute(
			$q=sprintf("
					INSERT
						shop_user_shops
					SET
						user_id = %u
						,name = %s
						,address1 = %s
						,address2 = %s
						,city = %s
						,zip = %s
						,phone = %s
						,website = %s
						,email = %s
						,rating = %u
						,lat = %f
						,`long` = %f
				"
				,$user_id
				,$db->Quote($data['name'])
				,$db->Quote($data['address1'])
				,$db->Quote($data['address2'])
				,$db->Quote($data['city'])
				,$db->Quote($data['zip'])
				,$db->Quote($data['phone'])
				,$db->Quote("")
				,$db->Quote("")
				,1
				,$lat
				,$long
			)
		);
	}
	echo "done<br />";
}

?>