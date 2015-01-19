<?php
	$config['psp']['name']		=	"Authorize";
	$config['psp']['business']	=	"liz@mayfairhouse.com";
	$config['psp']['test_mode']	=	false;
	$config['psp']['version']	=	3.1;
	$config['psp']['test_request']	= "false";
	$config['psp']['method']	=	"cc";
	$config['psp']['type']		=	"AUTH_ONLY";
	$config['psp']['type2']		=	"AuthOnly";
	
	$config['psp']['api_login_id'] = "9U8qkFu4dNK";
	$config['psp']['transaction_key'] = "9h9r4Ca45F448jMX";
	
	if($config['psp']['test_mode']) {
		$config['psp']['url'] = "https://test.authorize.net/gateway/transact.dll?test=123";
		$config['psp']['profile_url'] = "https://test.authorize.net/profile/";
	}
	else {
		$config['psp']['url'] = "https://secure.authorize.net/gateway/transact.dll";
		$config['psp']['profile_url'] = "https://secure.authorize.net/profile/";
	}
	