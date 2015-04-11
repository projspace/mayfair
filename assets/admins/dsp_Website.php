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
<h1>Website Editor</h1><hr />
<table class="values">
	<tr>
		<th>Page name</th>
		<th>Content</th>
		<th>&nbsp;</th>
	</tr>
<?
	while($row=$website->FetchRow())
	{
		if($class=="light")
			$class="dark";
		else
			$class="light";
		echo "<tr>
				<td class=\"$class\">{$row['name']}</td>
				<td class=\"$class\">".truncate(strip_tags($row['content']),50)."...</td>
				<td class=\"$class right\">";
		if($row['id']>1)
		{
			echo "<button class=\"form\" title=\"Delete\" onclick=\"return postbackConf(
					this
					,'removePage'
					,['pageid']
					,[{$row['id']}]
					,'delete'
					,'page')\"><img src=\"{$config['dir']}images/admin/delete.png\" width=\"16\" height=\"16\" alt=\"Delete\" /></button>";
		}
		echo "<a title=\"Edit\" href=\"{$config['dir']}index.php?fuseaction=admin.editPage&amp;pageid={$row['id']}\"><img src=\"{$config['dir']}images/admin/edit.png\" width=\"16\" height=\"16\" alt=\"Edit\" /></a></td>
			</tr>";
	}
?>
</table>
<div class="right"><button class="add" onclick="window.location='<? $config['dir']; ?>index.php?fuseaction=admin.addPage';">Add Page</button></div>