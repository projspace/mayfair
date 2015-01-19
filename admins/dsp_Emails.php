<form id="postback" method="post" action="none"></form>
<h1>Emails</h1>

<table class="values nocheck">
	<tr>
		<th>Name</th>
		<th>To</th>
		<th style="width:170px;">&nbsp;</th>
	</tr>
	<?
		while($row=$emails->FetchRow())
		{
			if($class=="light")
				$class="dark";
			else
				$class="light";

			echo "<tr class=\"$class\">
				<td>{$row['name']}</td>
				<td>{$row['to']}</td>
				<td class=\"right\">";
			if($acl->check("removeEmail"))
			{
				echo "<span class=\"button button-grey\"><input type=\"button\" value=\"Remove\" onclick=\"return postbackConf(
					this
					,'removeEmail'
					,['email_id']
					,[{$row['id']}]
					,'remove'
					,'email')\"/></span>\n";
			}
			if($acl->check("editEmail"))
				echo "<a class=\"button button-grey\" href=\"{$config['dir']}index.php?fuseaction=admin.editEmail&amp;email_id={$row['id']}\"><span>Edit</span></a>\n";
			echo "</td>
			</tr>";
		}
	?>
</table>
<? if($acl->check("addEmail")): ?>
	<div class="tab-panel-buttons clearfix">
		<a class="button button-small-add add right" href="<?= $config['dir'] ?>index.php?fuseaction=admin.addEmail"><span>Add Email</span></a>
	</div>
<? endif; ?>