<h1>Edit Listing</h1><hr />

<form method="post" action="<?= $config['dir'] ?>index.php?fuseaction=admin.pageListings&amp;pageid=<?= $pageid ?>&amp;parent_id=<?= $parent_id ?>&amp;listingid=<?= $listingid ?>&amp;act=save">

<div class="legend">Listing Details</div>
<div class="form">
	<label for="title">Title</label>
	<input type="text" id="title" name="title" value="<?= $listing->fields['title'] ?>" /><br />

	<label for="content">Content</label>
	<textarea id="content" name="content" rows="5" cols="40"><?= $listing->fields['content'] ?></textarea><br />
</div>

<div class="formRight">
	<input class="submit" type="submit" value="Continue" />
	<button class="finished" onclick="window.location='<?= $config['dir'] ?>index.php?fuseaction=admin.pageListings&amp;pageid=<?= $_REQUEST['pageid'] ?>&amp;parent_id=<?= $_REQUEST['parent_id'] ?>'; return false;">Cancel</button>
</div>
</form>