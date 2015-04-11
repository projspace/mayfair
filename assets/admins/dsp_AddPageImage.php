<form id="postback" method="post" action="none"></form>
<h1>New Image</h1>

<form id="frmForm" method="post" enctype="multipart/form-data" action="<?= $config['dir'] ?>index.php?fuseaction=admin.addPageImage&amp;pageid=<?=$_REQUEST['pageid'] ?>&amp;parent_id=<?=$_REQUEST['parent_id'] ?>&amp;act=save">
	<div id="tabs" class="form clearfix">
		<ul>
			<li><a href="#tabs-1">Details</a></li>
		</ul>
		<div id="tabs-1">
			<div class="form-field clearfix">
				<label for="image">File</label>
				<input type="file" id="image" name="image" />
			</div>
		</div>
	</div>
	<div class="tab-panel-buttons clearfix">
		<span class="button button-small submit">
			<input class="submit" type="submit" value="Continue" />
		</span>
		<a class="button button-grey" href="<?= $config['dir'] ?>index.php?fuseaction=admin.pageImages&amp;pageid=<?=$_REQUEST['pageid'] ?>&amp;parent_id=<?=$_REQUEST['parent_id'] ?>"><span>Cancel</span></a>
	</div>
</form>		