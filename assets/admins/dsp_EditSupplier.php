<form id="postback" method="post" action="none"></form>
<h1>Edit Supplier</h1>

<form method="post" action="<?= $config['dir'] ?>index.php?fuseaction=admin.editSupplier&act=save">
	<input type="hidden" name="supplier_id" value="<?=$supplier['id'] ?>" />
	
	<div id="tabs" class="form clearfix">
		<ul>
			<li><a href="#tabs-1">Details</a></li>
		</ul>
		<div id="tabs-1">
			<div class="form-field clearfix">
				<label for="name">Name</label>
				<input type="text" id="name" name="name" value="<?=$supplier['name'] ?>" />
			</div>
			<div class="form-field clearfix">
				<label for="address">Address</label>
				<textarea id="address" name="address" rows="5" cols="31"><?= $supplier['address']; ?></textarea>
			</div>
			<div class="form-field clearfix">
				<label for="postcode">Postcode</label>
				<input type="text" id="postcode" name="postcode" value="<?=$supplier['postcode'] ?>" />
			</div>
			<div class="form-field clearfix">
				<label for="country_id">Country</label>
				<select id="country_id" name="country_id">
				<?
					while($country=$countries->FetchRow())
					{
						echo "<option value=\"{$country['id']}\"";
						if($country['id']==$supplier['country_id'])
							echo " selected=\"selected\"";
						echo ">{$country['name']}</option>\n";
					}
				?>
				</select>
			</div>
			<div class="form-field clearfix">
				<label for="tel">Telephone</label>
				<input type="text" id="tel" name="tel" value="<?=$supplier['tel'] ?>" />
			</div>
			<div class="form-field clearfix">
				<label for="fax">Fax</label>
				<input type="text" id="fax" name="fax" value="<?=$supplier['fax'] ?>" />
			</div>
			<div class="form-field clearfix">
				<label for="email">Email</label>
				<input type="text" id="email" name="email" value="<?=$supplier['email'] ?>" />
			</div>
			<div class="form-field clearfix">
				<label for="notes">Notes</label>
				<textarea id="notes" name="notes" rows="5" cols="31"><?= $supplier['notes']; ?></textarea>
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