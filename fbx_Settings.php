<?
	if(!isset($attributes['fuseaction']))
	{
		$attributes['fuseaction'] = "home.main";
	}
	if(!isset($GLOBALS['self']))
	{
		$GLOBALS['self'] = "index.php";
	}
	$XFA = array();
	$Fusebox['layoutdir'] = "";
	$Fusebox['layoutfile'] = "fbx_DefaultLayout.php";
	$Fusebox['suppresserrors'] = false;
	if($Fusebox['ishomecircuit'])
	{
	}
	else
	{
	}
?>