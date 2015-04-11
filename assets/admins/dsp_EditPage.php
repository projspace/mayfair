<?= $validator->clientValidate(); ?>
<?= $validator->displayMessage(); ?>

<?
	if($page->fields['pendingadd']==1)
		alert("This page has not yet been accepted into the site by an editor, any changes here may not make it to the site","Pending Addition Detected");
	if($page->fields['pendingedit']==1)
		alert("This page has an already pending edit that has not been accepted by an editor, your changes to this page may be lost if the other revision is accepted","Pending Edit Detected");
	if($page->fields['pendingremove']==1)
		alert("This page is slated for removal from the site, your edit may never make it onto the site","Pending Removal Detected");
?>

<h1>Edit Page</h1>

<script type="text/javascript">var wysiwyg=true;</script>
<script type="text/javascript" src="<?= $config['dir'] ?>VLib/js/lib_MultiTabs.js"></script>

<form method="post" action="<?= $config['dir'] ?>index.php?fuseaction=admin.editPage&amp;act=save"<?= $validator->form() ?> <?= $wysiwyg->form(); ?>>

<input type="hidden" name="pageid" value="<?= safe($_REQUEST['pageid'],1) ?>" />
<input type="hidden" name="parent_id" value="<?= safe($_REQUEST['parent_id'],1) ?>" />
<input type="hidden" name="layoutid" value="<?= safe($page->fields['layoutid'],1) ?>" />
<input type="hidden" name="pagetype" value="<?= safe($page->fields['pagetype'],1) ?>" />

	<div id="tabs" class="form clearfix">
		<ul>
			<li><a href="#tabs-1">Page Details</a></li>
			<? if($page->fields['pagetype']==1) : ?>
				<li><a href="#tabs-2">Content</a></li>
				<li><a href="#tabs-3">Meta Tags</a></li>
			<? endif; ?>
		</ul>
		<div id="tabs-1">
			<div class="form-field clearfix">
				<label for="name">Page Name</label>
				<input type="text" id="name" name="name" value="<?= safe(disp($_POST['name'],$page->fields['name'])) ?>" />
				<?= $validator->display("name"); ?>
			</div>
			<div class="form-field clearfix">
				<label for="valid_from">Valid From</label>
				<input type="text" class="calendar" id="valid_from" name="valid_from" value="<?= format_date(disp($_POST['valid_from'],$page->fields['valid_from'])) ?>" />
				<?= $validator->display("valid_from"); ?>
			</div>
			<div class="form-field clearfix">
				<label for="valid_to">Valid To</label>
				<input type="text" class="calendar" id="valid_to" name="valid_to" value="<?= format_date(disp($_POST['valid_to'],$page->fields['valid_to'])) ?>" />
				<?= $validator->display("valid_to"); ?>
			</div>
			<div class="form-field clearfix">
				<label for="hidden">Visibility</label>
				<select id="hidden" name="hidden">
					<option value="0"<? if($page->fields['hidden']==0) echo " selected=\"selected\""; ?>>Visible</option>
					<option value="1"<? if($page->fields['hidden']==1) echo " selected=\"selected\""; ?>>Hidden</option>
				</select>
			</div>
			<div class="form-field clearfix">
				<label for="menu">Visible in the navigation: Menu</label>
				<input type="checkbox" name="menu" value="1" <? if($page->fields['menu']) echo "checked=\"checked\""; ?> /><br />
			</div>
			<div class="form-field clearfix">
				<label for="megafooter">Visible in the navigation: Mega footer</label>
				<input type="checkbox" id="megafooter" name="megafooter" value="1" <? if($page->fields['megafooter']) echo "checked=\"checked\""; ?> /><br />
			</div>
			<div class="form-field clearfix">
				<label for="footer">Visible in the navigation: Footer</label>
				<input type="checkbox" id="footer" name="footer" value="1" <? if($page->fields['footer']) echo "checked=\"checked\""; ?> /><br />
			</div>
			<div class="form-field clearfix">
				<label for="sidebar">Visible in the navigation: Sidebar</label>
				<input type="checkbox" id="sidebar" name="sidebar" value="1" <? if($page->fields['sidebar']) echo "checked=\"checked\""; ?> /><br />
			</div>
			<? if($acl->check("instantAdd")) : ?>
				<div class="form-field clearfix">
					<label for="instant">Instant Action</label>
					<input type="checkbox" id="instant" name="instant" checked="checked" />
				</div>
			<? endif; ?>
		</div>
		<? if($page->fields['pagetype']==1) : ?>
			<div id="tabs-2">
				<div class="form-field clearfix">
					<label>Description</label><br />
					<?= $wysiwyg->editor($content->fields['content']); ?>
				</div>
				<div class="form-field clearfix">
					<label>Short Description</label><br />
					<?= $wysiwyg->editor($content->fields['description']); ?>
				</div>
			</div>
			<div id="tabs-3">
				<div class="form-field clearfix">
					<label for="meta_title">META Title</label>
					<input type="text" id="meta_title" name="meta_title" value="<?= safe(disp($_POST['meta_title'],$content->fields['meta_title'])) ?>" />
				</div>
				<div class="form-field clearfix">
					<label for="meta_keywords">META Keywords</label>
					<textarea id="meta_keywords" name="meta_keywords"><?= safe(disp($_POST['meta_keywords'],$content->fields['meta_keywords'])) ?></textarea>
				</div>
				<div class="form-field clearfix">
					<label for="meta_description">META Description</label>
					<textarea id="meta_description" name="meta_description"><?= safe(disp($_POST['meta_description'],$content->fields['meta_description'])) ?></textarea>
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