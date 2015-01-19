<form id="postback" method="post" action="none"></form>
<h1>Edit Item</h1>

<form enctype="multipart/form-data" method="post" action="<?= $config['dir'] ?>index.php?fuseaction=admin.editCategoryBoxItem&amp;item_id=<?=$item['id'] ?>&amp;box_id=<?=$_REQUEST['box_id'] ?>&amp;category_id=<?=$_REQUEST['category_id'] ?>&amp;act=save">
	<div id="tabs" class="form clearfix">
		<ul>
			<li><a href="#tabs-1">Details</a></li>
		</ul>
		<div id="tabs-1">
			<div class="form-field clearfix">
				<label for="title">Title</label>
				<input type="text" id="title" name="title" value="<?=$item['title'] ?>" />
			</div>
			<div class="form-field clearfix">
				<label for="small_title">Small Title</label>
				<input type="text" id="small_title" name="small_title" value="<?=$item['small_title'] ?>" />
			</div>
			<div class="form-field clearfix">
				<label for="label">Label</label>
				<select id="label" name="label">
					<option value="none">None</option>
					<option value="new_product">New Product</option>
					<option value="best_seller">Best Seller</option>
					<option value="on_sale">On Sale</option>
					<option value="bloch_stars">Bloch Stars</option>
				</select>
			</div>
			<div class="form-field clearfix">
				<label for="link">Link</label>
				<select id="link" name="link">
					<option value="">None</option>
				<?
					while($row = $products->FetchRow())
					{
						$name = array();
						foreach((array)unserialize($row['trail']) as $key=>$var)
						{
							if($key < 2)
								continue;
							$name[] = '<strong>'.$var['name'].'</strong>';
						}
						if(count($name))
							$name = array(implode(' / ', $name));
						if($row['type'] == 'product')
							$name[] = $row['name'];
							
						$id = $row['type'].'_'.$row['id'];
						if($id == $item['link_type'].'_'.$item['link_id'])
							echo '<option value="'.$id.'" selected="selected">'.implode(' &gt; ', $name).'</option>';
						else
							echo '<option value="'.$id.'">'.implode(' &gt; ', $name).'</option>';
					}
				?>
				</select>
			</div>
			<div class="form-field clearfix">
			<?	
				switch($item['type'])
				{
					case 'big1':
						$size[0] = 490;
						$size[1] = 430;
						break;
					case 'small':
						$size[0] = 242;
						$size[1] = 430;
						break;
					case 'big2':
						$size[0] = 366;
						$size[1] = 496;
						break;
					case 'small1':
						$size[0] = 366;
						$size[1] = 196;
						break;
					case 'small2':
						$size[0] = 366;
						$size[1] = 296;
						break;
					default:
						break;
				}
			?>
				<label for="image">Image <?=count($size)?$size[0].' x '.$size[1]:'' ?></label>
				<input type="file" id="image" name="image" /><br/>
				<?
					if($item['image_type']!="")
						echo "<label>&nbsp;</label><img src=\"{$config['dir']}images/box_items/{$item['id']}.{$item['image_type']}?time=".time()."\" width=\"{$size[0]}\" /><br />";
				?>
			</div>
		</div>
	</div>
	<div class="tab-panel-buttons clearfix">
		<span class="button button-small submit">
			<input class="submit" type="submit" value="Continue" />
		</span>
		<a class="button button-grey" href="<?= $config['dir'] ?>index.php?fuseaction=admin.categoryBoxItems&box_id=<?=$_REQUEST['box_id'] ?>&category_id=<?=$_REQUEST['category_id'] ?>"><span>Cancel</span></a>
	</div>
</form>		