<form id="postback" method="post" action="none">
<? if($acl->check("instantChangeLayout")) : ?>
<label for="instant">Instant Action</label>
<input type="checkbox" id="instant" name="instant" checked="checked" /><br />
<? endif; ?>
</form>
<h1>Page Layout File</h1>
<table class="values nocheck">
	<tr>
		<th>Name</th>
		<th>Description</th>
		<th>Editable Sections</th>
		<th style="width:170px;">&nbsp;</th>
	</tr>
<?
	while($row=$layouts->FetchRow())
	{
		if($class=="light")
			$class="dark";
		else
			$class="light";

		if($row['id']==$layoutid)
			$class="selected";

		echo "<tr class=\"$class\">
			<td>{$row['name']}</td>
			<td>".truncate($row['description'],100)."...</td>
			<td>".str_replace("\n"," | ",$row['sections'])."</td>
			<td class=\"right\">";
		if($row['id']!=$layoutid)
			echo "<span class=\"button button-grey\"><input type=\"button\" value=\"Use Layout\" onclick=\"return postbackConf(
				this
				,'pageLayout'
				,['pageid','parent_id','layoutid','act']
				,[{$_REQUEST['pageid']},{$_REQUEST['parent_id']},{$row['id']},'change']
				,'use'
				,'layout file')\"/></span>\n";
		echo "</td>
		</tr>";
	}
?>
</table>
<div class="right">
	<a class="button button-grey" href="<?= $config['dir'] ?>index.php?fuseaction=admin.pages&amp;parent_id=<?= $_REQUEST['parent_id'] ?>"><span>Cancel</span></a>
</div>