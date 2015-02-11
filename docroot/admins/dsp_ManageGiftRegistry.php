<h1>Manage Gift Registry</h1>

<form method="post" action="<?= $config['dir'] ?>index.php?fuseaction=admin.manageGiftRegistry&amp;act=save">
	<div id="tabs" class="form clearfix">
		<ul>
			<li><a href="#tabs-1">Details</a></li>
		</ul>
		<div id="tabs-1">
			<div class="form-field clearfix">
				<label for="gift_days_advance">Event date<em>days in advance</em></label>
				<input type="text" id="gift_days_advance" name="gift_days_advance" value="<?=$gift_registry['days_advance'] ?>" />
			</div>
			<div class="form-field clearfix">
				<label for="gift_name_min_length">Name minimum length</label>
				<input type="text" id="gift_name_min_length" name="gift_name_min_length" value="<?=$gift_registry['name_min_length'] ?>" />
			</div>
			<div class="form-field clearfix">
				<label for="gift_phone_min_digits">Phone minimum digits</label>
				<input type="text" id="gift_phone_min_digits" name="gift_phone_min_digits" value="<?=$gift_registry['phone_min_digits'] ?>" />
			</div>
			<div class="form-field clearfix">
				<label for="gift_pagination">Pagination</label>
				<input type="text" id="gift_pagination" name="gift_pagination" value="<?=$gift_registry['pagination'] ?>" />
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