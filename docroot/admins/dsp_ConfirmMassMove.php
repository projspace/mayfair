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
<h1>Are you sure?</h1>
<p>You are about to perform a mass move of one of more products or references, displayed below.  Please note that this action cannot be undone automatically.  Please double check the list to ensure any products you do not wish to move are unchecked.  Once you are sure, please click on the "Confirm" button below.</p>
<br />
<form method="post" action="<?= $config['dir'] ?>index.php?fuseaction=admin.massMove&act=confirm">
<input type="hidden" name="category_id" value="<?=$_POST['category_id'] ?>">
<table class="values">
<?
	if(isset($confirm))
	{
		echo "<tr><td colspan=\"2\"><h3>Products</h3></td></tr>";
		while($row=$confirm->FetchRow())
		{
			if($class=="light")
				$class="dark";
			else
				$class="light";
			echo "<tr>
				<td class=\"{$class} thin\"><input type=\"checkbox\" name=\"product[]\" value=\"{$row['id']}\" checked=\"checked\"></td>
				<td class=\"{$class}\">{$row['name']}</td>
			</tr>";
		}
	}
	if(isset($confirm_ref))
	{
		echo "<tr><td colspan=\"2\"><h3>References</h3></td></tr>";
		while($row=$confirm_ref->FetchRow())
		{
			if($class=="light")
				$class="dark";
			else
				$class="light";
			echo "<tr>
				<td class=\"{$class} thin\"><input type=\"checkbox\" name=\"ref[]\" value=\"{$row['id']}\" checked=\"checked\"></td>
				<td class=\"{$class}\">{$row['name']}</td>
			</tr>";
		}
	}
?>
</table>
<div class="right">
	<span class="button button-grey"><input type="submit" value="Confirm" class="submit" /></span>
</div>
</form>
