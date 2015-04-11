<form id="postback" method="post" action="none"></form>
<h1>Edit Image</h1>

<form id="frmForm" method="post" enctype="multipart/form-data" action="<?= $config['dir'] ?>index.php?fuseaction=admin.editPageImage&amp;pageid=<?=$_REQUEST['pageid'] ?>&amp;parent_id=<?=$_REQUEST['parent_id'] ?>&amp;image_id=<?=$image['id'] ?>&amp;act=save">
	<div id="tabs" class="form clearfix">
		<ul>
			<li><a href="#tabs-1">Details</a></li>
			<li><a href="#tabs-2">Image</a></li>
		</ul>
		<div id="tabs-1">
			<div class="form-field clearfix">
				<label for="image">File</label>
				<input type="file" id="image" name="image" />
			</div>
            <? if($image['pageid'] == 1): ?>
            <div class="form-field clearfix">
				<label for="metadata_title">Title</label>
				<input type="text" id="metadata_title" name="metadata[title]" value="<?= $image['metadata']['title'] ?>" />
			</div>
            <div class="form-field clearfix">
				<label for="metadata_description">Description</label>
				<input type="text" id="metadata_description" name="metadata[description]" value="<?= $image['metadata']['description'] ?>" />
			</div>
            <div class="form-field clearfix">
				<label for="metadata_url">URL</label>
				<input type="text" id="metadata_url" name="metadata[url]" value="<?= $image['metadata']['url'] ?>" />
			</div>
            <? endif; ?>
		</div>
        <div id="tabs-2">
            <img src="<?= $config['dir'] ?>images/page/image_<?= $image['id'].'.'.$image['image_type'] ?>?t=<?= time() ?>" />
		</div>
	</div>
	<div class="tab-panel-buttons clearfix">
		<span class="button button-small submit">
			<input class="submit" type="submit" value="Continue" />
		</span>
		<a class="button button-grey" href="<?= $config['dir'] ?>index.php?fuseaction=admin.pageImages&amp;pageid=<?=$_REQUEST['pageid'] ?>&amp;parent_id=<?=$_REQUEST['parent_id'] ?>"><span>Cancel</span></a>
	</div>
</form>		