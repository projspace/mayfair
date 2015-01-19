<?
error_reporting(E_ALL);
ini_set('display_errors','1');
set_time_limit(0);
try 
{
	include("../lib/cfg_Config.php");
	include("../lib/adodb/adodb.inc.php");
	include("../lib/act_OpenDB.php");
	
	$blocks = $db->Execute(
		$sql = sprintf("
			SELECT
				*
			FROM
				shop_deep_linking
			ORDER BY
				title ASC
		"
	));
	
	$json = array();
	foreach($blocks as $row)
		$json[] = array('id'=>$row['id'], 'title'=>$row['title']);
		
	echo json_encode($json);
}
catch (Exception $e)
{
	echo json_encode(false);
}
?>