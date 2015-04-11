<form id="postback" method="post" action="none"></form>
<h1>New Width</h1>

<?=$validator->displayMessage() ?>

<form method="post" action="<?= $config['dir'] ?>index.php?fuseaction=admin.addWidth&amp;act=save">
	<div id="tabs" class="form clearfix">
		<ul>
			<li><a href="#tabs-1">Details</a></li>
		</ul>
		<div id="tabs-1">
			<div class="form-field clearfix">
				<label for="name">Name<em>(used on the site)</em></label>
				<input type="text" id="name" name="name" value="" />
			</div>
			<div class="form-field clearfix">
				<label for="code">Code<em>(used on the CSV file)</em></label>
				<input type="text" id="code" name="code" value="" />
			</div>
		</div>
	</div>
	<div class="tab-panel-buttons clearfix">
		<span class="button button-small submit">
			<input class="submit" type="submit" value="Continue" />
		</span>
		<a class="button button-grey" href="<?= $config['dir'] ?>index.php?fuseaction=admin.widths"><span>Cancel</span></a>
	</div>
</form>		