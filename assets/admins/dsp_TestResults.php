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
<h1>Test Rules</h1><hr>
<p>The results of your test are shown below:</p>

<div class="legend">Transaction Variables</div>
<div class="form">
<?
	$keys=array_keys($vars);
	for($i=0;$i<count($keys);$i++)
	{
		if($keys[$i]!="stack")
		{
			echo "<label for=\"{$keys[$i]}\">{$keys[$i]}</label>
				<span  id=\"{$keys[$i]}\">{$vars[$keys[$i]]}</span><br />";
		}
	}
?>
</div>
<div class="formRight">
	<button class="submit" onClick="history.back();">Back</button>
</div>