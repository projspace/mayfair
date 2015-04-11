<?php
	$config['psp']['name']		=	"Authorize";
	$config['psp']['business']	=	"sales@bloch.co.uk";
	$config['psp']['test_mode']	=	true;
	$config['psp']['version']	=	3.1;
	$config['psp']['method']	=	"cc";
	$config['psp']['type']		=	"AUTH_ONLY";
	$config['psp']['type2']		=	"AuthOnly";

	/*
	$config['psp']['api_login_id'] = "9vkB7xr4YX44";//"8ES4VU2n4thA";
	$config['psp']['transaction_key'] = "26kpLp959z2UhWX8";//"6Mh7Yz99d4JSqK75";//"2Y59s63T8jrXQ9s7";
	
	$config['psp']['url'] = "https://secure.authorize.net/gateway/transact.dll";
	$config['psp']['profile_url'] = "https://secure.authorize.net/profile/";
	
	if($config['psp']['test_mode'])
		$config['psp']['test_request']	= "TRUE";
	else
		$config['psp']['test_request']	= "FALSE";
	
	*/
	if($config['psp']['test_mode']) {
		
		$config['psp']['api_login_id'] = "6zzFJ79fSm";
		$config['psp']['transaction_key'] = "5v8dd4rKqe5G6X59";
		
		$config['psp']['url'] = "https://test.authorize.net/gateway/transact.dll";
		$config['psp']['profile_url'] = "https://test.authorize.net/profile/";
		
		$config['psp']['test_request']	= "TRUE";
	
	} else {
		
		$config['psp']['api_login_id'] = "9vkB7xr4YX44";//"8ES4VU2n4thA";
		$config['psp']['transaction_key'] = "26kpLp959z2UhWX8";//"6Mh7Yz99d4JSqK75";//"2Y59s63T8jrXQ9s7";
		
		$config['psp']['url'] = "https://secure.authorize.net/gateway/transact.dll";
		$config['psp']['profile_url'] = "https://secure.authorize.net/profile/";
		
		$config['psp']['test_request']	= "FALSE";
	}	