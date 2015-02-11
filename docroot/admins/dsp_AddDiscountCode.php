<h1>Add Discount Code(s)</h1>

<form method="post" action="<?= $config['dir'] ?>index.php?fuseaction=admin.addDiscountCode&amp;act=save">

	<div id="tabs" class="form clearfix">
		<ul>
			<li><a href="#tabs-1">Details</a></li>
		</ul>
		<div id="tabs-1">
			<div class="form-field clearfix">
				<label for="value">Value</label>
				<input type="text" id="value" name="value" value="0.00" />
				<select name="value_type" style="width:100px;">
					<option value="fixed">$</option>
					<option value="percent">%</option>
				</select>
			</div>
			<div class="form-field clearfix">
				<label for="expiry_date">Expiry Date</label>
				<input type="text" class="calendar" id="expiry_date" name="expiry_date" /><br />
			</div>
			<div class="form-field clearfix">
				<label for="min_order">Minimum Order Value</label>
				<input type="text" id="min_order" name="min_order" value="0.00" /><br />
			</div>
			<div class="form-field clearfix">
				<label for="count">Count</label>
				<input type="text" id="count" name="count" value="1" /><br />	
			</div>
			<div class="form-field clearfix">
				<label for="length">Character Count</label>
				<input type="text" id="length" name="length" value="10" /><br />
			</div>
			<div class="form-field clearfix">
				<label for="use_count">Use Count</label>
				<input type="text" id="use_count" name="use_count" value="1" /><br />
			</div>
			<div class="form-field clearfix">
				<label for="all_users">Usable by all users</label>
				<input type="checkbox" id="all_users" name="all_users" value="1" /><br />
			</div>
		</div>
	</div>
	
	<div class="tab-panel-buttons clearfix">
		<span class="button button-small submit">
			<input class="submit" type="submit" value="Continue" />
		</span>
		<a class="button button-grey" href="<?= $config['dir'] ?>index.php?fuseaction=admin.discountCodes"><span>Cancel</span></a>
	</div>
	
</form>