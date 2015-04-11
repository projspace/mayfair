<form id="postback" method="post" action="none"></form>
<h1>Add Layout File</h1><hr />

<form method="post" action="<?= $config['dir'] ?>index.php?fuseaction=admin.addLayout&amp;act=add">

<div class="legend">Layout Details</div>
<div class="form">
	<label for="name">Name</label>
	<input type="text" id="name" name="name" value="" /><br />

	<label for="filename">Filename</label>
	<input type="text" id="filename" name="filename" value="" /><br />

	<label for="description">Description</label>
	<textarea id="description" name="description"></textarea><br />

	<label for="sections">Sections (1 per line)</label>
	<textarea id="sections" name="sections"></textarea><br />

	<label for="default">Default layout?</label>
	<input type="checkbox" id="default" name="default" /><br />
</div>

<div class="formRight">
	<input class="submit" type="submit" value="Continue" />
	<button class="finished" onclick="window.location='<?= $config['dir'] ?>index.php?fuseaction=admin.layouts'; return false;">Cancel</button>
</div>