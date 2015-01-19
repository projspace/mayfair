<form id="postback" method="post" action="none"></form>
<h1>Add Brand</h1>

<form <?= $wysiwyg->form(); ?> enctype="multipart/form-data" method="post" action="<?= $config['dir'] ?>index.php?fuseaction=admin.addBrand&amp;act=add">
	
	<div id="tabs" class="form clearfix">
		<ul>
			<li><a href="#tabs-1">Details</a></li>
			<li><a href="#tabs-2">Description</a></li>
		</ul>
		<div id="tabs-1">
			<div class="form-field clearfix">
				<label for="name">Name</label>
				<input type="text" id="name" name="name" value="" />
			</div>
            <div class="form-field clearfix">
				<label for="image">Image</label>
				<input type="file" id="image" name="image" />
			</div>
            <div class="form-field clearfix">
				<label for="hidden">Hide brand</label>
				<input type="checkbox" id="hidden" name="hidden" value="1" />
			</div>
		</div>
		<div id="tabs-2">
			<?= $wysiwyg->editor(); ?>
		</div>
	</div>
	<div class="tab-panel-buttons clearfix">
		<span class="button button-small submit">
			<input class="submit" type="submit" value="Continue" />
		</span>
		<a class="button button-grey" href="<?= $config['dir'] ?>index.php?fuseaction=admin.brands"><span>Cancel</span></a>
	</div>
</form>