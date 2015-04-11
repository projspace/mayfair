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
<h1>Access Groups</h1>
<table class="values nocheck">
	<tr>
		<th>Name</th>
		<th style="width:160px;">&nbsp;</th>
	</tr>
<?
	while($row=$groups->FetchRow())
	{
		if($class=="light")
			$class="dark";
		else
			$class="light";
		echo "<tr>
				<td class=\"$class\">{$row['name']}</td>
				<td class=\"$class right\">";
		if($row['id']!=1)
		{
			if($acl->check("removeACLGroup"))
			{
				echo "<span class=\"button button-grey\"><input type=\"button\" class=\"form\" title=\"End Session\" onclick=\"return postbackConf(
							this
							,'removeACLGroup'
							,['group_id']
							,[{$row['id']}]
							,'remove'
							,'access group')\" value=\"Delete\" /></span>";
			}
			if($acl->check("editACLGroup"))
				echo "<a class=\"button button-grey\" title=\"Edit\" href=\"{$config['dir']}index.php?fuseaction=admin.editACLGroup&amp;group_id={$row['id']}\"><span>Edit</span></a>";
		}
		echo "
				</td>
			</tr>";
	}
?>
</table>
<? if($acl->check("addACLGroup")): ?>
<div class="tab-panel-buttons clearfix">
	<a class="button button-small-add add right" href="<?= $config['dir'] ?>index.php?fuseaction=admin.addACLGroup"><span>Add Group</span></a>
</div>
<? endif; ?>