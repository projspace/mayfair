<form id="postback" method="post" action="none"></form>
<h1>Add Supplier</h1>

<form method="post" action="<?= $config['dir'] ?>index.php?fuseaction=admin.addSupplier&act=add">
	
	<div id="tabs" class="form clearfix">
		<ul>
			<li><a href="#tabs-1">Details</a></li>
		</ul>
		<div id="tabs-1">
			<div class="form-field clearfix">
				<label for="name">Name</label>
				<input type="text" id="name" name="name" value="" />
			</div>
			<div class="form-field clearfix">
				<label for="address">Address</label>
				<textarea id="address" name="address" rows="5" cols="31"></textarea>
			</div>
			<div class="form-field clearfix">
				<label for="postcode">Postcode</label>
				<input type="text" id="postcode" name="postcode" value="" />
			</div>
			<div class="form-field clearfix">
				<label for="country_id">Country</label>
				<select id="country_id" name="country_id">
				<?
					while($country=$countries->FetchRow())
					{
						echo "<option value=\"{$country['id']}\"";
						if($country['id']==$config['defaultcountry_id'])
							echo " selected=\"selected\"";
						echo ">{$country['name']}</option>\n";
					}
				?>
				</select>
			</div>
			<div class="form-field clearfix">
				<label for="tel">Telephone</label>
				<input type="text" id="tel" name="tel" value="" />
			</div>
			<div class="form-field clearfix">
				<label for="fax">Fax</label>
				<input type="text" id="fax" name="fax" value="" />
			</div>
			<div class="form-field clearfix">
				<label for="email">Email</label>
				<input type="text" id="email" name="email" value="" />
			</div>
			<div class="form-field clearfix">
				<label for="notes">Notes</label>
				<textarea id="notes" name="notes" rows="5" cols="31"></textarea>
			</div>
		</div>
	</div>
	<div class="tab-panel-buttons clearfix">
		<span class="button button-small submit">
			<input class="submit" type="submit" value="Continue" />
		</span>
		<a class="button button-grey" href="<?= $config['dir'] ?>index.php?fuseaction=admin.brands"><span>Cancel</span></a>
	</div>
</form>