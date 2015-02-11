<form id="postback" method="post" action="none"></form>
<h1>Home Banner</h1>

<form enctype="multipart/form-data" method="post" action="<?= $config['dir'] ?>index.php?fuseaction=admin.homeBanner&amp;act=save">
	<input type="hidden" name="type" value="single" />
	<div id="tabs" class="form clearfix">
		<ul>
			<li><a href="#tabs-1">Details</a></li>
		</ul>
		<div id="tabs-1">
			<!--<div class="form-field clearfix">
				<label for="type">Banner Type</label>
				<select id="type" name="type">
					<option value="single" <? if($home_banner['type'] == 'single'): ?>selected="selected"<? endif; ?>>Single Image</option>
					<option value="multiple" <? if($home_banner['type'] == 'multiple'): ?>selected="selected"<? endif; ?>>Multiple Images</option>
				</select>
			</div>-->
			<div class="form-field clearfix">
				<label for="url">URL</label>
				<input type="text" id="url" name="url" value="<?=$home_banner['url'] ?>" />
			</div>
			<div class="form-field clearfix">
				<label for="image">Image 733 x 823</label>
				<input type="file" id="image" name="image" /><br/>
				<?
					if($home_banner['image_type']!="")
						echo "<label>&nbsp;</label><img src=\"{$config['dir']}images/home_banner.{$home_banner['image_type']}?time=".time()."\" width=\"400\" /><br />";
				?>
			</div>
		</div>
	</div>
	<div class="tab-panel-buttons clearfix">
		<span class="button button-small submit">
			<input class="submit" type="submit" value="Continue" />
		</span>
		<a class="button button-grey" href="<?= $config['dir'] ?>index.php?fuseaction=admin.start"><span>Cancel</span></a>
	</div>
</form>		