<form id="postback" method="post" action="none"></form>
<h1>Edit Banner</h1>

<form enctype="multipart/form-data" method="post" action="<?= $config['dir'] ?>index.php?fuseaction=admin.editHomeBanner&amp;banner_id=<?=$home_banner['id'] ?>&amp;act=save">
	<div id="tabs" class="form clearfix">
		<ul>
			<li><a href="#tabs-1">Details</a></li>
		</ul>
		<div id="tabs-1">
			<div class="form-field clearfix">
				<label for="label">Label</label>
				<input type="text" id="label" name="label" value="<?=$home_banner['label'] ?>" />
			</div>
			<div class="form-field clearfix">
				<label for="description">Description</label>
				<input type="text" id="description" name="description" value="<?=$home_banner['description'] ?>" />
			</div>
			<div class="form-field clearfix">
				<label for="url">URL</label>
				<input type="text" id="url" name="url" value="<?=$home_banner['url'] ?>" />
			</div>
			<div class="form-field clearfix">
				<label for="image">Image 733 x 765</label>
				<input type="file" id="image" name="image" /><br/>
				<?
					if($home_banner['image_type']!="")
						echo "<label>&nbsp;</label><img src=\"{$config['dir']}images/home_banners/{$home_banner['id']}.{$home_banner['image_type']}?time=".time()."\" width=\"400\" /><br />";
				?>
			</div>
		</div>
	</div>
	<div class="tab-panel-buttons clearfix">
		<span class="button button-small submit">
			<input class="submit" type="submit" value="Continue" />
		</span>
		<a class="button button-grey" href="<?= $config['dir'] ?>index.php?fuseaction=admin.homeBanners"><span>Cancel</span></a>
	</div>
</form>		