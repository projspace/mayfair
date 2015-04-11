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
<h1>Stock Control</h1><hr>
<div class="center">
<?
	$width=0;
	//History
	if($history)
	{
		for($i=0;$i<count($history);$i++)
		{
			echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\">
					<tr>";
			if($width>0)
				echo "<td width=\"$width\"><img src=\"{$config['dir']}images/trans.gif\" width=\"$width\" height=\"1\"></td>";
			echo "		<td class=\"darker\" width=\"40\"><img src=\"{$config['dir']}images/admin/folder_open.gif\"></td>
						<td class=\"darker\"><a href=\"{$config['dir']}index.php?fuseaction=admin.stock&id={$history[$i]['id']}\" class=\"white\"><font size=\"4\"><strong>{$history[$i]['name']}</strong></font></a></td>
					</tr>
				</table>";
			$width=$width+30;
		}
	}
	//Child categories
	$keys=$children->GetKeys();
	while($row=$children->FetchRow())
	{
		echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\">
				<tr>
					<td width=\"$width\"><img src=\"{$config['dir']}images/trans.gif\" width=\"$width\" height=\"1\"></td>
					<td class=\"darker\" width=\"40\"><img src=\"{$config['dir']}images/admin/folder_closed.png\"></td>
					<td class=\"darker\"><a class=\"white\" href=\"{$config['dir']}index.php?fuseaction=admin.stock&id={$row[$keys['id']]}\"><font size=\"4\"><strong>{$row[$keys['name']]}</strong></font></a></td>
				</tr>
			</table>\n";
	}
	echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">
			<tr>
			<td width=\"".($width+2)."\"><img src=\"{$config['dir']}images/trans.gif\" width=\"".($width+2)."\" height=\"1\"></td>
			<td>
				<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\">";
	if($products->RecordCount()>0)
	{
		echo "<tr>
					<td class=\"heading\">Key:</td>
					<td class=\"heading\">Name</td>
					<td class=\"heading\">Description</td>
					<td class=\"heading\"><p align=\"right\">Price</p></td>
					<td class=\"heading\"><p align=\"right\">Stock</p></td>
					<td class=\"heading\"><p align=\"right\">Trigger</p></td>
				</tr>
				<form method=\"post\" action=\"{$config['dir']}index.php?fuseaction=admin.stock&act=update&id=".$_REQUEST['id']."\">";
		$keys=$products->GetKeys();
		$count=0;
		while($row=$products->FetchRow())
		{
			if($row[$keys['parent_id']]==0)
			{
				if($row[$keys['shop_products.stock']]==0)
					$class="red";
				else if($row[$keys['shop_products.stock']]<$row[$keys['shop_products.trigger']])
					$class="amber";
				else
					$class="dark";
				echo "<tr><input type=\"hidden\" name=\"product$count\" value=\"{$row[$keys['shop_products.id']]}\">
						<td class=\"$class\" width=\"40\">
							<img src=\"{$config['dir']}images/admin/product.gif\" width=\"28\" height=\"22\" alt=\"Product\" title=\"Product\">
						</td>
						<td class=\"$class\"><strong>{$row[$keys['shop_products.name']]}</strong></td>
						<td class=\"$class\">".truncate($row[$keys['shop_products.description']],50)."...</td>
						<td class=\"$class\"><p align=\"right\"><strong>$".price($row[$keys['shop_products.price']])."</strong></p></td>
						<td class=\"$class\"><p align=\"right\"><input type=\"text\" name=\"stock$count\" value=\"{$row[$keys['shop_products.stock']]}\" size=\"4\"></p></td>
						<td class=\"$class\"><p align=\"right\"><input type=\"text\" name=\"trigger$count\" value=\"{$row[$keys['shop_products.trigger']]}\" size=\"4\"></p></td>
					</tr>
					<tr height=\"1\">
						<td class=\"none\"><img src=\"{$config['dir']}images/trans.gif\" width=\"100%\" height=\"1\"></td>
					</tr>";
					$count++;
			}
		}
	}
	echo "<input type=\"hidden\" name=\"count\" value=\"$count\">";
?>
						<tr>
							<td colspan="6"><div class="right"><input class="submit" type="Submit" value="Update"></div></td>
						</tr>
						</form>
					</table>
				</tr>
			</td>
		</tr>
	</table>
</div>