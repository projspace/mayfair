<?= $validator->clientValidate(); ?>
<?= $validator->displayMessage(); ?>

<h1>Add Page</h1>

<script type="text/javascript">var wysiwyg=true;</script>
<script type="text/javascript" src="<?= $config['dir'] ?>VLib/js/lib_MultiTabs.js"></script>

<form method="post" action="<?= $config['dir'] ?>index.php?fuseaction=admin.addPage&amp;act=add"<?= $validator->form() ?>>

	<input type="hidden" name="parent_id" value="<?= safe($_REQUEST['parent_id'],1) ?>" />
	<input type="hidden" name="layoutid" value="<?= safe($_REQUEST['layoutid'],1) ?>" />
	<input type="hidden" name="pagetype" value="<?= safe($_REQUEST['pagetype'],1) ?>" />

	<div id="tabs" class="form clearfix">
		<ul>
			<li><a href="#tabs-1">Page Details</a></li>
			<? if($_REQUEST['pagetype']==1) : ?>
				<li><a href="#tabs-2">Content</a></li>
				<li><a href="#tabs-3">Meta Tags</a></li>
			<? endif; ?>
		</ul>
		<div id="tabs-1">
			<div class="form-field clearfix">
				<label for="name">Page Name</label>
				<input type="text" id="name" name="name" value="<?= safe($_POST['name']) ?>" />
				<?= $validator->display("name"); ?>
			</div>
			<div class="form-field clearfix">
				<label for="valid_from">Valid From</label>
				<input type="text" class="calendar" id="valid_from" name="valid_from" value="<?= safe($_POST['name']) ?>" />
			</div>
			<div class="form-field clearfix">
				<label for="valid_to">Valid To</label>
				<input type="text" class="calendar" id="valid_to" name="valid_to" value="<?= safe($_POST['name']) ?>" />
			</div>
			<div class="form-field clearfix">
				<label for="hidden">Visibility</label>
				<select id="hidden" name="hidden">
					<option value="0">Visible</option>
					<option value="1">Hidden</option>
				</select>
			</div>
			<div class="form-field clearfix">
				<label for="menu">Visible in the navigation: Menu</label>
				<input type="checkbox" name="menu" value="1"/><br />
			</div>
			<div class="form-field clearfix">
				<label for="megafooter">Visible in the navigation: Mega footer</label>
				<input type="checkbox" id="megafooter" name="megafooter" value="1"/><br />
			</div>
			<div class="form-field clearfix">
				<label for="footer">Visible in the navigation: Footer</label>
				<input type="checkbox" id="footer" name="footer" value="1"/><br />
			</div>
			<div class="form-field clearfix">
				<label for="sidebar">Visible in the navigation: Sidebar</label>
				<input type="checkbox" id="sidebar" name="sidebar" value="1"/><br />
			</div>
			<? if($acl->check("instantAdd")) : ?>
				<div class="form-field clearfix">
					<label for="instant">Instant Action</label>
					<input type="checkbox" id="instant" name="instant" checked="checked" /><br />
				</div>
			<? endif; ?>
		</div>
		<? if($_REQUEST['pagetype']==1) : ?>
			<div id="tabs-2">
				<div class="form-field clearfix">
					<label>Description</label><br />
					<?= $wysiwyg->editor($_POST['content'][0]); ?>
				</div>
				<div class="form-field clearfix">
					<label>Short Description</label><br />
					<?= $wysiwyg->editor($_POST['content'][1]); ?>
				</div>
			</div>
			<div id="tabs-3">
				<div class="form-field clearfix">
					<label for="meta_title">META Title</label>
					<input type="text" id="meta_title" name="meta_title" value="<?= (isset($_POST['meta_title'])) ? $_POST['meta_title'] : $current_site->fields['meta_title'] ?>" /><br />
				</div>
				<div class="form-field clearfix">
					<label for="meta_keywords">META Keywords</label>
					<textarea id="meta_keywords" name="meta_keywords"><?= (isset($_POST['meta_keywords'])) ? $_POST['meta_keywords'] : $current_site->fields['meta_keywords'] ?></textarea><br />
				</div>
				<div class="form-field clearfix">
					<label for="meta_description">META Description</label>
					<textarea id="meta_description" name="meta_description"><?= (isset($_POST['meta_description'])) ? $_POST['meta_description'] : $current_site->fields['meta_description'] ?></textarea><br />
				</div>
			</div>
		<? endif; ?>
	</div>

	<div class="tab-panel-buttons clearfix">
		<span class="button button-small submit">
			<input class="submit" type="submit" value="Continue" />
		</span>
		<a class="button button-grey" href="<?= $config['dir'] ?>index.php?fuseaction=admin.pages&amp;parent_id=<?= $_REQUEST['parent_id'] ?>"><span>Cancel</span></a>
	</div>

</form>