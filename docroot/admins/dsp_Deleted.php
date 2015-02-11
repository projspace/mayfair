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
<h1>Deleted Pages</h1>
<table class="values nocheck">
	<tr>
		<th>Page Name</th>
		<th>Content</th>
		<th>&nbsp;</th>
	</tr>
<?
	while($row=$deleted->FetchRow())
	{
		if($class=="light")
			$class="dark";
		else
			$class="light";

		echo "<tr class=\"$class\">";

		//Main Display
		echo "<td><a href=\"{$config['dir']}index.php?fuseaction=admin.pages&amp;parent_id={$row['id']}\">{$row['name']}</a></td>
				<td>".truncate($row['content'],100)."...</td>
				<td class=\"right\">";
		//Compare button
		echo "<a class=\"button button-grey\" href=\"\" title=\"Undelete\" onclick=\"return postbackConf(
				this
				,'unDelete'
				,['pageid','parent_id']
				,[{$row['id']},{$row['parent_id']}]
				,'undelete'
				,'page')\"><span>Undelete</span></a>\n";
		echo "</td>
			</tr>";
	}
?>
</table>