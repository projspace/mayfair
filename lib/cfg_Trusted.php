<?
	if(!isset($config))
		include("cfg_Config.php");
	if(!isset($db))
	{
		include("adodb/adodb.inc.php");
		include("act_OpenDB.php");
	}
	$sites=$db->Execute("
		SELECT
			*
		FROM
			cms_sites
	");
	$trusted_directories=array();
	$count=1;
	while($row=$sites->FetchRow())
	{
		$new[$count.'_img']=array($row['path']."images/website/",$config['dir']."images/website/");
		$new[$count.'_doc']=array($row['path']."downloads/",$config['dir']."downloads/");
		$trusted_directories[]=$new;
		$count++;
	}
/*	$trusted_directories = array(
		'1_img' => array('/var/www/vhosts/climateexchangeplc.com/httpdocs/images/website/', '/images/website/'),
		'1_doc' => array('/var/www/vhosts/climateexhcnageplc.com/httpdocs/downloads/', '/downloads/'),
	);*/
?>
