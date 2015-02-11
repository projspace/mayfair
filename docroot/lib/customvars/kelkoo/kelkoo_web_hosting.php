<label for="custom_kelkoo_type">Type</label>
<select id="custom_kelkoo_type" name="kelkoo[type]">
<? if(trim($custom["kelkoo_type"])!="") echo "<option>{$custom["kelkoo_type"]}</option>\n"; ?>
	<option>Macintosh</option>
	<option>Unix</option>
	<option>Windows</option>
</select><br />

<label for="custom_kelkoo_model_name">Package Name</label>
<input type="text" id="custom_kelkoo_package_name" name="kelkoo[field_d]" value="<?= $custom["kelkoo_field_d"] ?>" /><br />

<label for="custom_kelkoo_minimum_term">Minimum Term</label>
<input type="text" id="custom_kelkoo_minimum_term" name="kelkoo[field_e]" value="<?= $custom["kelkoo_field_e"] ?>" /> months<br />

<label for="custom_kelkoo_setup_cost">Setup Cost</label>
<input type="text" id="custom_kelkoo_setup_cost" name="kelkoo[field_f]" value="<?= $custom["kelkoo_field_f"] ?>" /><br />

<label for="custom_kelkoo_web_space">Web Space</label>
<input type="text" id="custom_kelkoo_web_space" name="kelkoo[field_g]" value="<?= $custom["kelkoo_field_g"] ?>" /> MB or Unlimited<br />

<label for="custom_kelkoo_monthly_bandwidth">Monthly Bandwidth</label>
<input type="text" id="custom_kelkoo_monthly_bandwidth" name="kelkoo[field_h]" value="<?= $custom["kelkoo_field_h"] ?>" /> GB or Unlimited<br />

<label for="custom_kelkoo_unique_codes">Unique Codes</label>
<input type="text" id="custom_kelkoo_unique_codes" name="kelkoo[field_k]" value="<?= $custom["kelkoo_field_k"] ?>" /><br />