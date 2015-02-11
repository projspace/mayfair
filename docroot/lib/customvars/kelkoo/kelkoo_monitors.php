
	<label for="custom_kelkoo_type">Type</label>
	<select id="" name="custom[kelkoo_type]">
	<? if(trim($custom["kelkoo_type"])!="") echo "<option>{$custom["kelkoo_type"]}</option>\n"; ?>
		<option>LCD</option>
		<option>CRT</option>
		<option>Plasma</option>
		<option>Touchscreen</option>
	</select><br />


	<label for="custom_kelkoo_manufacturer">Manufacturer</label>
	<input type="text" id="" name="custom[kelkoo_field_c]" value="<?= $custom["kelkoo_field_c"] ?>" /><br />


	<label for="custom_kelkoo_model_name">Model Name</label>
	<input type="text" id="" name="custom[kelkoo_field_d]" value="<?= $custom["kelkoo_field_d"] ?>" /><br />


	<label for="custom_kelkoo_Size">Size</label>
	<select id="" name="custom[kelkoo_field_i]">
	<? if(trim($custom["kelkoo_field_i"])!="") echo "<option>{$custom["kelkoo_field_i"]}</option>\n"; ?>
		<option value="1">Unknown</option>
<?
	for($i=2;$i<48;$i++)
		echo "<option value=\"$i\">$i inches</option>\n";
?>
	</select><br />


	<label for="custom_kelkoo_unique_codes">Unique Codes</label>
	<input type="text" id="" name="custom[kelkoo_field_k]" value="<?= $custom["kelkoo_field_k"] ?>" /><br />
