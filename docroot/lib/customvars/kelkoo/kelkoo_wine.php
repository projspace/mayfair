<label for="custom_kelkoo_type">Type</label>
<select id="custom_kelkoo_type" name="custom[kelkoo_type]">
<? if(trim($custom["kelkoo_type"])!="") echo "<option>{$custom["kelkoo_type"]}</option>\n"; ?>
	<option>Mixed Case</option>
	<option>Red</option>
	<option>White</option>
	<option>Rose</option>
	<option>Champagne</option>
</select><br />

<label for="custom_kelkoo_name">Name</label>
<input type="text" id="custom_kelkoo_name" name="custom[kelkoo_field_d]" value="<?= $custom["kelkoo_field_d"] ?>" /><br />

<label for="custom_kelkoo_vineyard">Vineyard</label>
<input type="text" id="custom_kelkoo_vineyard" name="custom[kelkoo_field_e]" value="<?= $custom["kelkoo_field_e"] ?>" /><br />

<label for="custom_kelkoo_vintage">Vintage</label>
<input type="text" id="custom_kelkoo_vintage" name="custom[kelkoo_field_f]" value="<?= $custom["kelkoo_field_f"] ?>" /><br />

<label for="custom_kelkoo_region">Region</label>
<input type="text" id="custom_kelkoo_region" name="custom[kelkoo_field_g]" value="<?= $custom["kelkoo_field_g"] ?>" /><br />

<label for="custom_kelkoo_country">Country</label>
<input type="text" id="custom_kelkoo_country" name="custom[kelkoo_field_h]" value="<?= $custom["kelkoo_field_h"] ?>" /><br />

<label for="custom_kelkoo_bottles">Number of Bottles</label>
<input type="text" id="custom_kelkoo_bottles" name="custom[kelkoo_field_i]" value="<?= $custom["kelkoo_field_i"] ?>" /><br />

<label for="custom_kelkoo_unique_codes">Unique Codes</label>
<input type="text" id="" name="custom[kelkoo_field_k]" value="<?= $custom["kelkoo_field_k"] ?>" /><br />