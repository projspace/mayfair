<form id="postback" method="post" action="none"></form>
<h1>Edit Type</h1>

<form method="post" action="<?= $config['dir'] ?>index.php?fuseaction=admin.editGiftType&amp;type_id=<?=$type['id'] ?>&amp;act=update">
	<div id="tabs" class="form clearfix">
		<ul>
			<li><a href="#tabs-1">Details</a></li>
		</ul>
		<div id="tabs-1">
			<div class="form-field clearfix">
				<label for="name">Name</label>
				<input type="text" id="name" name="name" value="<?=$type['name'] ?>" />
			</div>
		</div>
	</div>
	<div class="tab-panel-buttons clearfix">
		<span class="button button-small submit">
			<input class="submit" type="submit" value="Continue" />
		</span>
		<a class="button button-grey" href="<?= $config['dir'] ?>index.php?fuseaction=admin.giftTypes"><span>Cancel</span></a>
	</div>
</form>		