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
<h1>Layout Files</h1><hr />
<table class="values">
	<tr>
		<th>Name</th>
		<th>Filename</th>
		<th>Description</th>
		<th>Editable Sections</th>
		<th>&nbsp;</th>
	</tr>
<?
	while($row=$layouts->FetchRow())
	{
		if($class=="light")
			$class="dark";
		else
			$class="light";

		echo "<tr class=\"$class\">
			<td>{$row['name']}</td>
			<td>{$row['filename']}</td>
			<td>".truncate($row['description'],100)."...</td>
			<td>".str_replace("\n"," | ",$row['sections'])."</td>
			<td class=\"right\">";
		if($row['id']!=$layoutid)
		{
			echo "<button title=\"Remove\" onclick=\"return postbackConf(
				this
				,'removeLayout'
				,['layoutid']
				,[{$row['id']}]
				,'remove'
				,'layout file')\"><img src=\"{$config['dir']}images/admin/delete.png\" width=\"16\" height=\"16\" alt=\"Remove\" /></button>\n";
		}
		echo "<a href=\"{$config['dir']}index.php?fuseaction=admin.editLayout&amp;layoutid={$row['id']}\"><img src=\"{$config['dir']}images/admin/edit.png\" width=\"16\" height=\"16\" alt=\"Edit\" /></a>\n";
		echo "</td>
		</tr>";
	}
?>
</table>
<div class="right">
	<button class="add" onclick="window.location='<?= $config['dir'] ?>index.php?fuseaction=admin.addLayout'; return false;">Add Layout</button>
</div>