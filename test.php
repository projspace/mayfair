<?
	exit;phpinfo();exit;
    include("lib/cfg_Config.php");
	include("lib/adodb/adodb.inc.php");
	include("lib/act_OpenDB.php");
	include("lib/lib_Email.php");

	$sent = $mail->sendMessage(array('link'=>$config["dir"].'index.php?fuseaction=admin.viewGiftRegistry&list_id='.$list_id),"NewGiftRegistry",'marian@webstarsltd.com',"");
	var_dump($sent);exit;
	
	$result = $db->Execute(
		sprintf("
			SELECT
				*
			FROM
				shop_sessions
			WHERE
				session_id=%s
		"
			//,$db->Quote('1382429069fef5cb7e6b4ed36')
			,$db->Quote('13824394833b06dea5994699a')
		)
	);
	echo '<pre>';
	var_dump($result->FetchRow());
	echo '</pre>';
	exit;
	
	$result = $db->Execute(sprintf("SELECT id,packing,gift_message FROM `shop_orders` ORDER BY id DESC LIMIT 100"));
	echo '<pre>';
	while($row = $result->FetchRow())
		var_dump($row);
	echo '</pre>';exit;
	
	
	
	include("lib/lib_Email.php");
	
/*	$host = trim($_REQUEST['server'])?:'smtp.postmarkapp.com';
	$port = ($_REQUEST['port']+0)?:2525;
	
	echo 'Opening socket:<br />server: '.$host.'<br />port: '.$port.'<br />';
	
	$status = fsockopen($host,    # the host of the server
						$port,    # the port to use
						$errno,   # error number if any
						$errstr,  # error message if any
						10);   # give up after ? secs
	var_dump($status, $errno, $errstr);exit;*/
	
	$sent=$mail->sendMessage(array(),"AdminSendPassword",'marian@webstarsltd.com',"");
	var_dump($sent);
	
	/*set_time_limit(0);
	include("lib/cfg_Config.php");
	include("lib/adodb/adodb.inc.php");
	include("lib/act_OpenDB.php");
	include("lib/lib_Search.php");

	$search=new Search($config);
	
	$products = $db->Execute(
		$sql = sprintf("
			SELECT
				*
			FROM
				shop_products
			WHERE
				id > 1
		"
	));
	while($row = $products->FetchRow())
		$search->update("product",$row['id']+0,$row['name'],strip_tags($row['description']),array('code'=>$row['code'], 'description'=>strip_tags($row['short_description'])));

	$products = $db->Execute(
		$sql = sprintf("
			SELECT
				cms_pages.id
				,cms_pages.name
				,cms_content.content
				,cms_content.description
			FROM
				cms_pages
				,cms_content
			WHERE
				cms_pages.id = cms_content.pageid
			AND
				cms_pages.content_revision = cms_content.revision
			AND
				cms_pages.deleted = 0
		"
	));
	while($row = $products->FetchRow())
		$search->update("page",$row['id']+0,$row['name'],strip_tags($row['content']),array('description'=>strip_tags($row['description'])));

	die('Done');*/

	/*var_dump($_SERVER['HTTP_USER_AGENT']);
	var_dump(preg_match('/(android)|(blackberry)|(ipad)|(iphone)|(ipod)|(iemobile)|(opera mobile)|(palmos)|(webos)|(googlebot-mobile)/i', $_SERVER['HTTP_USER_AGENT']));
	exit;*/


	include("lib/cfg_Config.php");
	include("lib/lib_Search.php");

	$search=new Search($config);
	/*$docs = array();
	for ($count = 0; $count < $search->_index->maxDoc(); $count++)
	{
		if ($search->_index->isDeleted($count))
		{
			$document = $search->_index->getDocument($count);

			$type = strtolower(trim($document->getFieldValue('type')));
			if($type == 'product')
				$docs[$document->getFieldValue('code')] = $document->getFieldValue('search_key');
		}
	}
	var_export($docs);
	exit;*/

	$hits=$search->find('L5947');

	$docs = array();
	foreach($hits as $hit)
	{
		$type = strtolower(trim($hit->getDocument()->getFieldValue('type')));

		if($type == 'product')
		{
			$doc = array('type'=>$type
					, 'key_id'=>$hit->getDocument()->getFieldValue('key_id')
					, 'search_key'=>$hit->getDocument()->getFieldValue('search_key')
					, 'title'=>$hit->getDocument()->getFieldValue('title')
					, 'abridged'=>$hit->getDocument()->getFieldValue('abridged')
					, 'fields'=>$hit->getDocument()->getFieldNames());

			$doc['code'] = $hit->getDocument()->getFieldValue('code');
			$doc['description'] = $hit->getDocument()->getFieldValue('description');
			$docs[] = $doc;
		}
	}
	var_export($docs);
?>