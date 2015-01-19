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
<h1>Rollback Page</h1><hr />
<table class="values">
	<tr>
		<th>Rev</th>
		<th>Timestamp</th>
		<th>User</th>
		<th>Page Name</th>
		<th>Layout</th>
		<th>Content</th>
		<th>&nbsp;</th>
	</tr>
<?
	while($row=$edits->FetchRow())
	{
		if($class=="light")
			$class="dark";
		else
			$class="light";
		if($row['revision']==$page->fields['revision'])
			$class="selected";

		echo "<tr class=\"$class\">";

		//Main Display
		echo "<td>{$row['revision']}</td>
				<td>{$row['time']}</td>
				<td>{$row['username']}</td>
				<td><strong>{$row['name']}</strong></a></td>
				<td>{$row['layout_name']}</td>
				<td>".truncate($row['content'],50)."</td>
				<td class=\"right\">";
		//Compare button
		if($row['revision']!=$page->fields['revision'])
			echo "<a title=\"Compare\" href=\"{$config['dir']}index.php?fuseaction=admin.rollback&amp;pageid={$_REQUEST['pageid']}&amp;parent_id={$_REQUEST['parent_id']}&amp;revision={$row['revision']}&amp;act=compare\"><img src=\"{$config['dir']}images/admin/view.png\" width=\"16\" height=\"16\" alt=\"Compare\"></a>\n";
		echo "</td>
			</tr>";
	}
?>
</table>
<div class="right">
	<button class="finished" onclick="window.location='<?= $config['dir'] ?>index.php?fuseaction=admin.pages&amp;parent_id=<?= $_REQUEST['parent_id'] ?>'; return false;">Cancel</button>
</div>