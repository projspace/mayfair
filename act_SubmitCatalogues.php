<?php
$response = array();
$src = $_POST;

if(!$src['name'])
    $response['error'] = 'Please enter your name';
else if(!$src['email'])
    $response['error'] = 'Please enter your email';
else{
    require_once 'lib_Snoopy.php';
    require_once 'lib_Email.php';

   $vars = array('file' => $config['catalogues'][$src['catalogue']]);
   $mail->sendMessage($vars, "CatalogueSubscribe", $src['email'], $src['name']);

	$data["em_wfs_formfield_3098807"] = $src['name'];
	$data["em_wfs_formfield_3098808"] = $src['email'];
	$ch=curl_init("http://www.vision6.com.au/em/forms/subscribe.php?db=383876&s=99035&a=20565&k=d56b307");

			curl_setopt($ch,CURLOPT_POST,1);
			curl_setopt($ch,CURLOPT_POSTFIELDS,$data);

			ob_start();
			curl_exec($ch);
			$verify=ob_get_contents();
			ob_end_clean();
			curl_close($ch);

	$response['error'] = "OK";
}

die($response['error']);
