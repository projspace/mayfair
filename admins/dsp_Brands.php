<form id="postback" method="post" action="none"></form>
<h1>Brands</h1>
<table class="values nocheck">
	<tr>
		<th>Name</th>
		<th style="width:170px;">&nbsp;</th>
	</tr>
<?
	while($row=$brands->FetchRow())
	{
		if($class=="light")
			$class="dark";
		else
			$class="light";

		echo "<tr>
				<td class=\"$class\"><strong>{$row['name']}</strong></td>
				<td class=\"$class right\">";
		if($row['id']>1 && $acl->check("removeBrand"))
			echo "<span class=\"button button-grey\"><input type=\"button\" value=\"Remove\" onclick=\"return postbackConf(
				this
				,'removeBrand'
				,['brand_id']
				,[{$row['id']}]
				,'remove'
				,'brand')\"/></span>\n";
		if($acl->check("editBrand"))
			echo "<a class=\"button button-grey\" href=\"{$config['dir']}index.php?fuseaction=admin.editBrand&amp;brand_id={$row['id']}\"><span>Edit</span></a>\n";
		echo "</tr>";
	}
?>
</table>
<? if($acl->check("addBrand")): ?>
<div class="right">
	<a class="button button-small-add add" href="<?= $config['dir'] ?>index.php?fuseaction=admin.addBrand"><span>Add Brand</span></a>
</div>
<? endif; ?>