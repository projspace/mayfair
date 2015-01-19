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
			echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\" class=\"values nocheck\"><tr>";
			if($width>0)
				echo "<td width=\"$width\" style=\"border-bottom:1px solid #EAEAEB;\"><img src=\"{$config['dir']}images/trans.gif\" width=\"$width\" height=\"1\"></td>";
			echo "		<td class=\"darker\" style=\"border-bottom:1px solid #EAEAEB;\" width=\"40\"><img src=\"{$config['dir']}images/admin/folder_open.png\"></td>
						<td class=\"darker\" style=\"border-bottom:1px solid #EAEAEB;\"><a href=\"{$config['dir']}index.php?fuseaction=admin.move&category_id={$history[$i]['id']}&product_id={$_REQUEST['product_id']}&refid={$_REQUEST['refid']}\" class=\"white\"><h1>{$history[$i]['name']}</h1></a></td>";
			if($history[$i]['id']==$_REQUEST['category_id'])
				echo "<td class=\"darker\"><div align=\"right\"><a class=\"white\" onClick=\"javascript:confMove()\">Move Here</a></div></td>";
			echo "		</tr>
				</table>";
			$width=$width+30;
		}
	}
	while($row=$children->FetchRow())
	{
		echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\" class=\"values nocheck\">
				<tr>
					<td width=\"$width\" style=\"border-bottom:1px solid #EAEAEB;\"><img src=\"{$config['dir']}images/trans.gif\" width=\"$width\" height=\"1\"></td>
					<td class=\"darker\" style=\"border-bottom:1px solid #EAEAEB;\" width=\"40\"><img src=\"{$config['dir']}images/admin/folder_closed.png\"></td>
					<td class=\"darker\" style=\"border-bottom:1px solid #EAEAEB;\"><a class=\"white\" href=\"{$config['dir']}index.php?fuseaction=admin.move&category_id={$row['id']}&product_id={$_REQUEST['product_id']}&refid={$_REQUEST['refid']}\"><h1>{$row['name']}</h1></a></td>
				</tr>
			</table>";
	}
?>
</div>