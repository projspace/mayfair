<?
	$status = 'SUCCESS';
	$url = 'http://smallbackroom.createsend.com/t/r/s/pujthi/';
	$post = 'cm-pujthi-pujthi='.urlencode(safe($_REQUEST['email']));
	$r=curl_init();
	curl_setopt($r,CURLOPT_SSL_VERIFYPEER,0);
	curl_setopt($r,CURLOPT_POST,true);
	curl_setopt($r,CURLOPT_URL,$url);
	curl_setopt($r,CURLOPT_POSTFIELDS,$post);
	curl_setopt($r,CURLOPT_REFERER,$config['protocol'].$config['url'].$config['dir']);
	ob_start();
	$succ=curl_exec($r);
	$output=ob_get_contents();
	ob_end_clean();
	if(curl_errno($r) != CURLE_OK)
		$status = 'ERROR';
	curl_close($r);
	die('var status="'.$status.'";');
?>