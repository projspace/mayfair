<form id="postback" method="post" action="none"></form>
<h1>Add Brand</h1>

<form <?= $wysiwyg->form(); ?> enctype="multipart/form-data" method="post" action="<?= $config['dir'] ?>index.php?fuseaction=admin.admin.addBrand&amp;act=add">
	
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
				<label for="url">Homepage</label>
				<input type="text" id="url" name="url" value="http://" />
			</div>
			<div class="form-field clearfix">
				<label for="supplier_id">Supplier</label>
				<select id="supplier_id" name="supplier_id">
				<?
					while($supplier=$suppliers->FetchRow())
					{
						echo "<option value=\"{$supplier['id']}\"";
						if($supplier['id']==1)
							echo " selected=\"selected\"";
						echo ">{$supplier['name']} : {$supplier['country_name']}</option>\n";
					}
				?>
				</select>
			</div>
			<div class="form-field clearfix">
				<label for="image">Image</label>
				<input type="file" id="image" name="image" />
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