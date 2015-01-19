<label for="custom_kelkoo_type">Type</label>
<select id="" name="custom[kelkoo_type]">
<? if(trim($custom["kelkoo_type"])!="") echo "<option>{$custom["kelkoo_type"]}</option>\n"; ?>
	<option>CompactFlash</option>
	<option>Internal</option>
	<option>Memory Stick</option>
	<option>MMC</option>
	<option>SD</option>
	<option>SmartMedia</option>
	<option>TransFlash</option>
	<option>XD</option>
</select><br />

<label for="custom_kelkoo_manufacturer">Manufacturer</label>
<input type="text" id="" name="custom[kelkoo_field_c]" value="<?= $custom["kelkoo_field_c"] ?>" /><br />

<label for="custom_kelkoo_model_name">Model Name</label>
<input type="text" id="" name="custom[kelkoo_field_d]" value="<?= $custom["kelkoo_field_d"] ?>" /><br />

<label for="custom_kelkoo_Size">Size</label>
<input type="text" id="" name="custom[kelkoo_field_e]" value="<?= $custom["kelkoo_field_e"] ?>"> MB<br />

<label for="custom_kelkoo_unique_codes">Unique Codes</label>
<input type="text" id="" name="custom[kelkoo_field_k]" value="<?= $custom["kelkoo_field_k"] ?>" /><br />