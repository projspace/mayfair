<?php
$gspecs=false;
$gspecsu=false;
function smarty_modifier_varval($specs,$var)
{
	global $gspecs,$gspecsu;
	if($specs!=$gspecs)
	{
		$gspecs=$specs;
		$gspecsu=fix_specs(unserialize($specs));
	}
	return $gspecsu[$var];
}

function fix_specs($specs)
{
	$s=array();
	for($i=0;$i<count($specs);$i++)
	{
		$s[$specs[$i]['name']]=$specs[$i]['value'];
	}
	return $s;
}
?>
