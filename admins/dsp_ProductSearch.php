<form id="postback" method="post" action="none"></form>
<h1 class="pageTitle">Product Search</h1>
<table class="values nocheck">
	<tr>
		<th>Style</th>
		<th>Product</th>
	</tr>
<?
	while($row=$products->FetchRow())
	{
		if($class=="light")
			$class="dark";
		else
			$class="light";

		echo "<tr class=\"$class\">
				<td><a href=\"{$config['dir']}index.php?fuseaction=admin.editProduct&category_id={$row['category_id']}&product_id={$row['id']}\">{$row['code']}</a></td>
				<td><a href=\"{$config['dir']}index.php?fuseaction=admin.editProduct&category_id={$row['category_id']}&product_id={$row['id']}\">{$row['name']}</a></td>
			</tr>";
	}
?>
</table>