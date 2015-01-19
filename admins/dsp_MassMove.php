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
<h1>Mass Move</h1>
<div class="center">
<form id="form" method="post" action="<?= $config['dir'] ?>index.php?fuseaction=admin.massMove">
	<input type="hidden" id="category_id" name="category_id" value="<?=$_POST['category_id'] ?>">
	<input type="hidden" id="act" name="act" value="">
<?
	$width=0;
	if($history)
	{
		for($i=1;$i<count($history);$i++)
		{
			echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\"><tr>";
			if($width>0)
				echo "<td width=\"$width\"><img src=\"{$config['dir']}images/trans.gif\" width=\"$width\" height=\"1\"></td>";
			echo "	  <td class=\"dark\" width=\"40\"><img src=\"{$config['dir']}images/admin/folder_open.png\"></td>
					<td class=\"dark\"><a onclick=\"document.getElementById('category_id').value={$history[$i]['id']}; document.getElementById('form').submit();\" class=\"white\">{$history[$i]['name']}</a></td>
					<td class=\"dark\"><div align=\"right\"><a class=\"button button-grey right\" href=\"#\" style=\"margin-bottom:5px;\" onClick=\"document.getElementById('category_id').value={$history[$i]['id']}; document.getElementById('act').value='move'; document.getElementById('form').submit();\"><span>Move Here</span></a></div></td>
				</tr>
			</table>";
			$width=$width+30;
		}
	}
	while($row=$children->FetchRow())
	{
		echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\">
				<tr>
					<td width=\"$width\"><img src=\"{$config['dir']}images/trans.gif\" width=\"$width\" height=\"1\"></td>
					<td class=\"dark\" width=\"40\"><img src=\"{$config['dir']}images/admin/folder_closed.png\"></td>
					<td class=\"dark\"><a class=\"white\" onclick=\"document.getElementById('category_id').value={$row['id']}; document.getElementById('form').submit();\">{$row['name']}</a></td>
					<td class=\"dark\"><div align=\"right\"><a class=\"button button-grey right\" style=\"margin-bottom:5px;\" href=\"#\" onclick=\"document.getElementById('category_id').value={$row['id']}; document.getElementById('act').value='move'; document.getElementById('form').submit();\"><span>Move Here</span></a></div></td>
				</tr>
			</table>";
	}

	if(isset($confirm))
	{
		while($row=$confirm->FetchRow())
		{
			echo "<input type=\"hidden\" name=\"product[]\" value=\"{$row['id']}\">\n";
		}
	}
	if(isset($confirm_ref))
	{
		while($row=$confirm_ref->FetchRow())
		{
			echo "<input type=\"hidden\" name=\"ref[]\" value=\"{$row['id']}\">\n";
		}
	}
?>
</form>
</div>
