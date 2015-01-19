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
<h1>Shipping Countries</h1>
<table class="values nocheck">
	<tr>
		<th>Country</th>
		<th>Area</th>
		<th style="width:160px;">&nbsp;</th>
	</tr>
<?
	while($row=$countries->FetchRow())
	{
		if($class=="light")
			$class="dark";
		else
			$class="light";
		echo "<tr>
				<td class=\"$class\">{$row['name']}</td>
				<td class=\"$class\">{$row['area_name']}</td>
				<td class=\"$class right\">";
		if($row['id']>1 && $acl->check("removeCountry"))
		{
			echo "<span class=\"button button-grey\"><input type=\"button\" title=\"Delete\" onclick=\"return postbackConf(
					this
					,'removeCountry'
					,['country_id','area_id']
					,[{$row['id']},{$_REQUEST['area_id']}]
					,'delete'
					,'country')\" value=\"Delete\" /></span>";
		}
		if($acl->check("editCountry"))
			echo "<a class=\"button button-grey\" title=\"Edit\" href=\"{$config['dir']}index.php?fuseaction=admin.editCountry&amp;country_id={$row['id']}\"><span>Edit</span></a></td>
			</tr>";
	}
?>
</table>
<? if($acl->check("addCountry")): ?>
<div class="tab-panel-buttons clearfix clear">
	<a class="button button-grey right" href="<?= $config['dir'] ?>index.php?fuseaction=admin.areas"><span>Cancel</span></a>
	<a class="button button-small-add add right" href="<?= $config['dir'] ?>index.php?fuseaction=admin.addCountry&area_id=<?=$_REQUEST['area_id'] ?>"><span>Add Country</span></a>
</div>
<? endif; ?>