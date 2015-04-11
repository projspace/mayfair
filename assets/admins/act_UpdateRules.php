<?
	/**
	 * e-Commerce System
	 * Copyright (c) 2002-2006 Philip John, All Rights Reserved.
	 * Author	: Philip John
	 * Version	: 6.0
	 *
	 * PROPRIETARY/CONFIDENTIAL.  Use is subject to license terms.
	 */
?>
<?
	//Compile code to put in cache
	$ok = true;
	try
	{
		$compiler=new Compiler();
		if($compiler->compile(stripslashes_if($_POST['code'])))
		{
			$cache=fopen("../lib/cfg_CheckoutRulesCache.php","w");
			fwrite($cache,trim($compiler->get()));
			fclose($cache);
		}

		$rules=fopen("../lib/cfg_CheckoutRules.php","w");
		fwrite($rules,"<? die(\"Move along, nothing to see here\"); ?>\n");
		fwrite($rules,"<?\n".stripslashes_if(trim($_POST['code']))."\n?>");
		fclose($rules);
	}
	catch (Exception $e)
	{
		$ok = false;
	}
	if(!$ok)
    	error("There was a problem whilst updating the rules, please try again.  If this persists please notify your designated support contact","System Error");
?>