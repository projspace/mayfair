<?= $validator->clientValidate(); ?>
<?= $validator->displayMessage(); ?>

<form id="postback" method="post" action="none"></form>
<h1>Add Meta Tag</h1><hr />

<form method="post" action="<?= $config['dir'] ?>index.php?fuseaction=admin.addMetaTag&amp;act=add"<?= $validator->form() ?>>

<div class="legend">Details</div>
<div class="form">
	<label for="name">Name</label>
	<input type="text" id="name" name="name" value="<?=$_POST['name'] ?>" />
	<?= $validator->display("name"); ?><br />

	<label for="description">Description</label>
	<textarea id="description" name="description"><?=$_POST['description'] ?></textarea><br />
</div>

<div class="formRight">
	<input class="submit" type="submit" value="Continue" />
	<button class="finished" onclick="window.location='<?= $config['dir'] ?>index.php?fuseaction=admin.meta_tags'; return false;">Cancel</button>
</div>