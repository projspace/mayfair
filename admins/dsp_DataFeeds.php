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
<h1>Product Feeds</h1><hr />
<form method="post" action="<?= $config['dir'] ?>index.php?fuseaction=admin.publishFeed">
<table class="values">
	<tr>
		<th>&nbsp;</th>
		<th>Feed Name</th>
		<th>Description</th>
		<th>&nbsp;</th>
	</tr>
<?
	while($row=$feeds->FetchRow())
	{
		if($class=="light")
			$class="dark";
		else
			$class="light";
		echo "<tr>
				<td class=\"$class thin\"><input type=\"checkbox\" name=\"feedid[]\" value=\"{$row['id']}\" /></td>
				<td class=\"$class\">{$row['name']}</td>
				<td class=\"$class\">".$row['description']."</td>
				<td class=\"$class right\">";
				
		if($acl->check("publishFeed"))
			echo"
				<button class=\"form\" title=\"Publich Feed\" onclick=\"return postbackConf(
					this
					,'publishFeed'
					,['feedid[]']
					,[{$row['id']}]
					,'publish'
					,'product feed')\"><img src=\"{$config['dir']}images/admin/publish.png\" width=\"16\" height=\"16\" alt=\"Publish Feed\" />
				</button>";
		echo "</td>
			</tr>";
	}
?>
</table>
<? if($acl->check("publishFeed")): ?>
<div class="right">
	<button class="feed" onclick="submit();">Publish Feeds</button>
</div>
<? endif; ?>
</form>