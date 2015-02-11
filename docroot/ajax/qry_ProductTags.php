<?
	include("../lib/cfg_Config.php");
	include("../lib/adodb/adodb.inc.php");
	include("../lib/act_OpenDB.php");
	
	$results=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				shop_meta_tags
			WHERE
				name LIKE '%%%s%%'
			ORDER BY
				name ASC
		"
		,$_REQUEST['query']
		)
	);
	while($row = $results->FetchRow())
		echo $row['name']."\t".$row['id']."\n";
?>