<label for="custom_kelkoo_type">Type</label>
<select id="custom_kelkoo_type" name="kelkoo[type]">
<? if(trim($custom["kelkoo_type"])!="") echo "<option>{$custom["kelkoo_type"]}</option>\n"; ?>
	<option>Alloys only</option>
	<option>Wheel & Tyre Package</option>
</select><br />

<label for="custom_kelkoo_manufacturer">Manufacturer</label>
<input type="text" id="custom_kelkoo_manufacturer" name="kelkoo[field_c]" value="<?= $custom["kelkoo_field_c"] ?>" /><br />

<label for="custom_kelkoo_product_name">Product Name</label>
<input type="text" id="custom_kelkoo_product_name" name="kelkoo[field_d]" value="<?= $custom["kelkoo_field_d"] ?>" /><br />

<label for="custom_kelkoo_finish">finish</label>
<select id="custom_kelkoo_finish" name="kelkoo[field_e]"
<? if(trim($custom['kelkoo_field_e']!="") echo "<option>{$custom['kelkoo_field_e']}</option>\n"; ?>
	<option>Black</option>
	<option>Bronze</option>
	<option>Chrome</option>
	<option>Gold</option>
	<option>Silver</option>
	<option>White</option>
</select><br />

<label for="custom_kelkoo_rim_diameter">Rim Diameter</label>
<input type="text" id="custom_kelkoo_rim_diameter" name="kelkoo[field_f]" value="<?= $custom["kelkoo_field_f"] ?>" /> "<br />

<label for="custom_kelkoo_"></label>
<input type="text" id="custom_kelkoo_" name="kelkoo[field_g]" value="<?= $custom["kelkoo_field_g"] ?>" /><br />

<label for="custom_kelkoo_"></label>
<input type="text" id="custom_kelkoo_" name="kelkoo[field_h]" value="<?= $custom["kelkoo_field_h"] ?>" /><br />

<label for="custom_kelkoo_"></label>
<input type="text" id="custom_kelkoo_" name="kelkoo[field_i]" value="<?= $custom["kelkoo_field_i"] ?>" /><br />

<label for="custom_kelkoo_"></label>
<input type="text" id="custom_kelkoo_" name="kelkoo[field_j]" value="<?= $custom["kelkoo_field_j"] ?>" /><br />

<label for="custom_kelkoo_unique_codes">Unique Codes</label>
<input type="text" id="custom_kelkoo_unique_codes" name="kelkoo[field_k]" value="<?= $custom["kelkoo_field_k"] ?>" /><br />