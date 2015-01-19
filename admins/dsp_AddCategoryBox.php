<form id="postback" method="post" action="none"></form>
<h1>New Box</h1>

<form method="post" action="<?= $config['dir'] ?>index.php?fuseaction=admin.addCategoryBox&amp;category_id=<?=$_REQUEST['category_id'] ?>&amp;act=save">
	<div id="tabs" class="form clearfix">
		<ul>
			<li><a href="#tabs-1">Details</a></li>
		</ul>
		<div id="tabs-1">
			<div class="form-field clearfix">
				<label for="type">Type</label>
				<select id="type" name="type">
					<option value="big_small">Big image + Small image</option>
					<option value="small_big">Small image + Big image</option>
					<option value="big_2_small">Big image + 2 Small images</option>
					<option value="2_small_big">2 Small images + Big image</option>
				</select>
			</div>
		</div>
	</div>
	<div class="tab-panel-buttons clearfix">
		<span class="button button-small submit">
			<input class="submit" type="submit" value="Continue" />
		</span>
		<a class="button button-grey" href="<?= $config['dir'] ?>index.php?fuseaction=admin.categoryBoxes&category_id=<?=$_REQUEST['category_id'] ?>"><span>Cancel</span></a>
	</div>
</form>		