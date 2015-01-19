<label for="custom_kelkoo_type">Type</label>
<select id="custom_kelkoo_type" name="kelkoo[type]">
<? if(trim($custom["kelkoo_type"])!="") echo "<option>{$custom["kelkoo_type"]}</option>\n"; ?>
	<option>Summer</option>
	<option>Winter</option>
	<option>Performance</option>
	<option>Ultra Performance</option>
	<option>Light Commercial</option>
	<option>Truck</option>
	<option>4x4</option>
	<option>Run Flat</option>
	<option>Compound</option>
	<option>Extra Load</option>
	<option>Reinforced</option>
	<option>Metric</option>
</select><br />

<label for="custom_kelkoo_manufacturer">Manufacturer</label>
<input type="text" id="custom_kelkoo_manufacturer" name="kelkoo[field_c]" value="<?= $custom["kelkoo_field_c"] ?>" /><br />

<label for="custom_kelkoo_model_name">Model Name</label>
<input type="text" id="custom_kelkoo_model_name" name="kelkoo[field_d]" value="<?= $custom["kelkoo_field_d"] ?>" /><br />

<label for="custom_kelkoo_full_specification">Full Specification</label>
<input type="text" id="custom_kelkoo_full_specification" name="kelkoo[field_e]" value="<?= $custom["kelkoo_field_e"] ?>" /><br />

<label for="custom_kelkoo_width">Width</label>
<input type="text" id="custom_kelkoo_width" name="kelkoo[field_f]" value="<?= $custom["kelkoo_field_f"] ?>" /> mm<br />

<label for="custom_kelkoo_profile">Profile</label>
<input type="text" id="custom_kelkoo_profile" name="kelkoo[field_g]" value="<?= $custom["kelkoo_field_g"] ?>" /><br />

<label for="custom_kelkoo_rim_diameter">Rim Diameter</label>
<input type="text" id="custom_kelkoo_rim_diameter" name="kelkoo[field_h]" value="<?= $custom["kelkoo_field_h"] ?>" /> "<br />

<label for="custom_kelkoo_speed_rating">Speed Rating</label>
<input type="text" id="custom_kelkoo_speed_rating" name="kelkoo[field_i]" value="<?= $custom["kelkoo_field_i"] ?>" /><br />

<label for="custom_kelkoo_load_index">Load Index</label>
<input type="text" id="custom_kelkoo_load_index" name="kelkoo[field_j]" value="<?= $custom["kelkoo_field_j"] ?>" /><br />

<label for="custom_kelkoo_unique_codes">Unique Codes</label>
<input type="text" id="custom_kelkoo_unique_codes" name="kelkoo[field_k]" value="<?= $custom["kelkoo_field_k"] ?>" /><br />