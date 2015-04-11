<form id="postback" method="post" action="none"></form>
<h1>Suppliers</h1>
<table class="values nocheck">
	<tr>
		<th>Name</th>
		<th>Address</th>
		<th>Country</th>
		<th>Tel</th>
		<th>Fax</th>
		<th>Email</th>
		<th>Notes</th>
		<th style="width:170px;">&nbsp;</th>
	</tr>
<?
	while($row=$suppliers->FetchRow())
	{
		if($class=="light")
			$class="dark";
		else
			$class="light";

		echo "<tr>
				<td class=\"$class\"><strong>{$row['name']}</strong></td>
				<td class=\"$class\">".truncate(str_replace("\n","<br>",$row['address'])." ".$row['postcode'],25)."...</td>
				<td class=\"$class\">{$row['country_name']}</td>
				<td class=\"$class\">{$row['tel']}</td>
				<td class=\"$class\">{$row['fax']}</td>
				<td class=\"$class\"><a href=\"mailto:{$row['email']}\">{$row['email']}</a></td>
				<td class=\"$class\">".truncate($row['notes'],20)."...</td>
				<td class=\"$class right\">";
		if($row['id']>1 && $acl->check("removeSupplier"))
			echo "<span class=\"button button-grey\"><input type=\"button\" value=\"Remove\" onclick=\"return postbackConf(
				this
				,'removeSupplier'
				,['supplier_id']
				,[{$row['id']}]
				,'remove'
				,'supplier')\"/></span>\n";
		if($acl->check("editSupplier"))
			echo "<a class=\"button button-grey\" href=\"{$config['dir']}index.php?fuseaction=admin.editSupplier&amp;supplier_id={$row['id']}\"><span>Edit</span></a>\n";
		echo "</tr>";
	}
?>
</table>
<? if($acl->check("addSupplier")): ?>
<div class="right">
	<a class="button button-small-add add" href="<?= $config['dir'] ?>index.php?fuseaction=admin.addSupplier"><span>Add Supplier</span></a>
</div>
<? endif; ?>