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
<div class="center">
<?
	$width=0;
	if($history)
	{
		for($i=1;$i<count($history);$i++)
		{
			echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\"><tr>";
			if($width>0)
				echo "<td width=\"$width\"><img src=\"{$config['dir']}images/trans.gif\" width=\"$width\" height=\"1\"></td>";
			echo "		<td class=\"darker\" width=\"40\"><img src=\"{$config['dir']}images/admin/folder_open.png\"></td>
						<td class=\"darker\"><a href=\"{$config['dir']}index.php?fuseaction=admin.moveCategory&parent_id={$history[$i]['id']}&category_id={$_REQUEST['category_id']}\" class=\"white\"><font size=\"4\"><strong>{$history[$i]['name']}</strong></font></a></td>";
			if($history[$i]['id']==$_REQUEST['parent_id'] && $_REQUEST['parent_id']!=$_REQUEST['category_id'])
				echo "<td class=\"darker\"><p align=\"right\"><a class=\"white\" onClick=\"javascript:confMove()\"><font size=\"1\"><strong>Move Here</strong></font></a></p></td>";
			echo "		</tr>
				</table>";
			$width=$width+30;
		}
	}
	while($row=$children->FetchRow())
	{
		echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\">
				<tr>
					<td width=\"$width\"><img src=\"{$config['dir']}images/trans.gif\" width=\"$width\" height=\"1\"></td>
					<td class=\"darker\" width=\"40\"><img src=\"{$config['dir']}images/admin/folder_closed.png\"></td>
					<td class=\"darker\"><a class=\"white\" href=\"{$config['dir']}index.php?fuseaction=admin.moveCategory&parent_id={$row['id']}&category_id={$_REQUEST['category_id']}\"><font size=\"4\"><strong>{$row['name']}</strong></font></a></td>
				</tr>
			</table>";
	}
?>
</div>