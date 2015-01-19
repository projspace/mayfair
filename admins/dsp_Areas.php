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
<form id="postback" method="post" action="none"></form>
<h1>Shipping Areas</h1>
<table class="values nocheck">
	<tr>
		<th>Name</th>
		<th>Countries</th>
		<th style="width:250px;">&nbsp;</th>
	</tr>
<?
	while($row=$areas->FetchRow())
	{
		if($class=="light")
			$class="dark";
		else
			$class="light";
		echo "<tr>
			<td class=\"$class\"><strong>{$row['name']}</strong></td>
			<td class=\"$class\">".$row['countries']."</td>
			<td class=\"$class right\">";
		if($row['id']>1 && $acl->check("removeArea"))
		{
			echo "<a class=\"button button-grey\" href=\"#\" title=\"Delete\" onclick=\"return postbackConf(
					this
					,'removeArea'
					,['area_id']
					,[{$row['id']}]
					,'delete'
					,'area');return false;\"><span>Delete</span></a>";
		}
		if($acl->check("countries"))
			echo "<a class=\"button button-grey\" title=\"Countries\" href=\"{$config['dir']}index.php?fuseaction=admin.countries&area_id={$row['id']}\"><span>Countries</span></a>";
		if($acl->check("editArea"))
			echo "<a class=\"button button-grey\" title=\"Edit\" href=\"{$config['dir']}index.php?fuseaction=admin.editArea&area_id={$row['id']}\"><span>Edit</span></a>
			</tr>";
	}
?>
</table>
<? if($acl->check("addArea")): ?>
<div class="right">
	<a class="button button-small-add add" href="#" onclick="window.location='<?= $config['dir'] ?>index.php?fuseaction=admin.addArea'; return false;"><span>Add Area</span></a>
</div>
<? endif; ?>