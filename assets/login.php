<?php
	include("lib/cfg_Config.php");
	include("lib/adodb/adodb.inc.php");
	include("lib/act_OpenDB.php");
	
	include("lib/lib_Validation.php");
	include("val_Login.php");
	
	if($_POST['is_post']+0)
	{
	}
	else
		include("dsp_Login.php");
?>
