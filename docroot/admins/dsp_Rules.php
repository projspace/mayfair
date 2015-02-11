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
<h1>Checkout Rules</h1><hr />
<form method="post" action="<?= $config['dir'] ?>index.php?fuseaction=admin.rules&amp;act=save">
<div class="legend">Checkout Rules</div>
<div class="form">
	<textarea name="code" rows="20" cols="100" class="code"><?
		ob_start();
		readfile("../lib/cfg_CheckoutRules.php");
		$content=ob_get_contents();
		$content=str_replace("<? die(\"Move along, nothing to see here\"); ?>","",ob_get_contents());
		$content=str_replace("<?","",$content);
		$content=str_replace("?>","",$content);
		$content=str_replace("<?=","",$content);
		$content=str_replace("<?php","",$content);
		ob_end_clean();
		echo trim($content);
	?></textarea><br />
</div>

<div class="formRight">
	<input class="submit" type="submit" value="Save">
</div>

</form>