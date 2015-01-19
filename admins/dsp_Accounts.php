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
<h1>Administrator Accounts</h1>
<table class="values nocheck">
	<tr>
		<th>Username</th>
		<th>Email</th>
		<th>Group</th>
		<th style="width:160px;">&nbsp;</th>
	</tr>
<?
	while($row=$accounts->FetchRow())
	{
		if($class=="light")
			$class="dark";
		else
			$class="light";

		echo "<tr>
				<td class=\"$class\"><strong>{$row['username']}</strong></td>
				<td class=\"$class\"><a href=\"mailto:{$row['email']}\">{$row['email']}</a></td>
				<td class=\"$class\">{$row['group_name']}</td>
				<td class=\"$class right\">";
		if($row['username']!="admin" && $row['id']!=$session->account_id && $acl->check("removeAccount"))
		{
			echo "<span class=\"button button-grey\"><input type=\"button\" title=\"Delete\" onclick=\"return postbackConf(
					this
					,'removeAccount'
					,['account_id']
					,[{$row['id']}]
					,'delete'
					,'administrator')\" value=\"Delete\" /></span>";
		}
		if($row['username']!="admin" && $acl->check("editAccount"))
			echo "<a class=\"button button-grey\" title=\"Edit\" href=\"{$config['dir']}index.php?fuseaction=admin.editAccount&account_id={$row['id']}\"><span>Edit</span></a>";
		echo "</td>
			</tr>";
	}
?>
</table>
<? if($acl->check("addAccount")): ?>
<div class="tab-panel-buttons clearfix">
	<a class="button button-small-add right" href="<?= $config['dir'] ?>index.php?fuseaction=admin.addAccount">
		<span>Add Account</span>
	</a>
</div>
<? endif; ?>