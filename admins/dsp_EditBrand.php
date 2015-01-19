<form id="postback" method="post" action="none"></form>
<h1>Edit Brand</h1>

<form <?= $wysiwyg->form(); ?> enctype="multipart/form-data" method="post" action="<?= $config['dir'] ?>index.php?fuseaction=admin.editBrand&amp;act=add">
	<input type="hidden" name="brand_id" value="<?=$brand['id'] ?>" />
	
	<div id="tabs" class="form clearfix">
		<ul>
			<li><a href="#tabs-1">Details</a></li>
			<li><a href="#tabs-2">Description</a></li>
		</ul>
		<div id="tabs-1">
			<div class="form-field clearfix">
				<label for="name">Name</label>
				<input type="text" id="name" name="name" value="<?=$brand['name'] ?>" />
			</div>
            <div class="form-field clearfix">
				<label for="image">Image</label>
				<input type="file" id="image" name="image" /><br />
				<?
					if($brand['imagetype']!="")
					{
						echo "<label>&nbsp;</label><img src=\"{$config['dir']}images/brand/thumbs/{$brand['id']}.{$brand['imagetype']}\" /><br />";
						echo "<label for=\"delete\">Delete Image</label>
							<input class=\"blank\" type=\"checkbox\" name=\"delete\" />\n";
					}
				?>
			</div>
            <div class="form-field clearfix">
				<label for="hidden">Hide brand</label>
				<input type="checkbox" id="hidden" name="hidden" value="1"<? if($brand['hidden']==1) echo " checked=\"checked\""; ?> />
			</div>
			<div class="form-field clearfix">
				<label for="content_visible">Show description</label>
				<input type="checkbox" id="content_visible" name="content_visible" value="1"<? if($brand['content_visible']==1) echo " checked=\"checked\""; ?> />
			</div>
			<div class="form-field clearfix">
				<label for="content_image">Description Image<em>Width 373px</em></label>
				<input type="file" id="content_image" name="content_image" /><br />
				<?
					if($brand['content_imagetype']!="")
					{
						echo "<label>&nbsp;</label><img src=\"{$config['dir']}images/brand/content/{$brand['id']}.{$brand['content_imagetype']}\" /><br />";
						echo "<label for=\"delete\">Delete Image</label>
							<input class=\"blank\" type=\"checkbox\" name=\"content_delete\" />\n";
					}
				?>
			</div>
		</div>
		<div id="tabs-2">
			<?= $wysiwyg->editor($brand['content']); ?>
		</div>
	</div>
	<div class="tab-panel-buttons clearfix">
		<span class="button button-small submit">
			<input class="submit" type="submit" value="Continue" />
		</span>
		<a class="button button-grey" href="<?= $config['dir'] ?>index.php?fuseaction=admin.brands"><span>Cancel</span></a>
	</div>
</form>