<?
	$config['psp']['name']="SecureHosting";
	$config['psp']['business']="sales@bloch.co.uk";
	$config['psp']['currency']="GBP";
	$config['psp']['item_name']="Bloch Shop";
	$config['psp']['test_mode']=true;
	
	$config['psp']['shreference']="SH217701";
	$config['psp']['checkcode']="375708";
	$config['psp']['password']="dx003934131L";
	
	if($config['psp']['test_mode'])
		$config['psp']['url'] = 'https://test.secure-server-hosting.com/secutran/';
	else
		$config['psp']['url'] = 'https://www.secure-server-hosting.com/secutran/';
?>
