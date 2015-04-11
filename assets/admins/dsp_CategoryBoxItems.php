<form id="postback" method="post" action="none"></form>
<h1>Box Items</h1>
<table class="values nocheck">
	<tr>
		<th>Type</th>
		<th>Image</th>
		<th>Title</th>
		<th>Label</th>
		<th style="width:60px;">&nbsp;</th>
	</tr>
<?
	while($row=$items->FetchRow())
	{
		if($class=="light")
			$class="dark";
		else
			$class="light";

		switch($row['type'])
		{
			case 'big1':
			case 'big2':
				$type = 'Big image';
				break;
			case 'small':
				$type = 'Small image';
				break;
			case 'small1':
				$type = 'Small image 1';
				break;
			case 'small2':
				$type = 'Small image 2';
				break;
			default:
				$type = 'None';
				break;
		}
		
		switch($row['label'])
		{
			case 'new_product':
				$label = 'New Product';
				break;
			case 'best_seller':
				$label = 'Best Seller';
				break;
			case 'on_sale':
				$label = 'On Sale';
				break;
			case 'bloch_stars':
				$label = 'Bloch Stars';
				break;
			case 'none':
			default:
				$label = 'None';
				break;
		}
			
		echo "<tr class=\"$class\">
			<td>{$type}</td>
			<td><img src=\"{$config['dir']}images/box_items/{$row['id']}.{$row['image_type']}?time=".time()."\" width=\"200\" alt=\"\" /></td>
			<td>{$row['title']}</td>
			<td>{$label}</td>
			<td class=\"right\">";
		if($acl->check("editCategoryBoxItem"))
			echo "<a class=\"button button-grey\" href=\"{$config['dir']}index.php?fuseaction=admin.editCategoryBoxItem&amp;item_id={$row['id']}&amp;box_id={$_REQUEST['box_id']}&amp;category_id={$_REQUEST['category_id']}\"><span>Edit</span></a>\n";
		echo "</td>
		</tr>";
	}
?>
</table>
<div class="right">
	<a class="button button-grey" href="<?= $config['dir'] ?>index.php?fuseaction=admin.categoryBoxes&category_id=<?=$_REQUEST['category_id'] ?>"><span>Back</span></a>
</div>