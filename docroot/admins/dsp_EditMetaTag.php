<?= $validator->clientValidate(); ?>
<?= $validator->displayMessage(); ?>

<form id="postback" method="post" action="none"></form>
<h1>Edit Meta Tag</h1><hr />

<form method="post" action="<?= $config['dir'] ?>index.php?fuseaction=admin.editMetaTag&amp;act=update"<?= $validator->form() ?>>
<input type="hidden" name="tag_id" value="<?=$meta_tag['id'] ?>" />

<div class="legend">Details</div>
<div class="form">
	<label for="name">Name</label>
	<input type="text" id="name" name="name" value="<?=disp($_POST['name'], $meta_tag['name']) ?>" />
	<?= $validator->display("name"); ?><br />

	<label for="description">Description</label>
	<textarea id="description" name="description"><?=disp($_POST['description'], $meta_tag['description']) ?></textarea><br />
</div>

<div class="formRight">
	<input class="submit" type="submit" value="Continue" />
	<button class="finished" onclick="window.location='<?= $config['dir'] ?>index.php?fuseaction=admin.meta_tags'; return false;">Cancel</button>
</div>