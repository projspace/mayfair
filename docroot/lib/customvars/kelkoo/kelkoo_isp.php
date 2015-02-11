
	<label for="custom_kelkoo_type">Type</label>
	<select id="" name="custom[kelkoo_type]">
	<? if(trim($custom["kelkoo_type"])!="") echo "<option>{$custom["kelkoo_type"]}</option>\n"; ?>
		<option>ADSL</option>
		<option>Cable</option>
		<option>Satellite</option>
	</select><br />


	<label for="custom_kelkoo_Package Name">Package Name</label>
	<input type="text" id="" name="custom[kelkoo_field_d]" value="<?= $custom["kelkoo_field_d"] ?>" /><br />


	<label for="custom_kelkoo_Minimum Term">Minimum Term</label>
	<input type="text" id="" name="custom[kelkoo_field_e]" value="<?= $custom["kelkoo_field_e"] ?>"> months<br />


	<label for="custom_kelkoo_Minimum Setup Cost">Minimum Setup Cost</label>
	£ <input type="text" id="" name="custom[kelkoo_field_f]" value="<?= $custom["kelkoo_field_f"] ?>" /><br />


	<label for="custom_kelkoo_Requirements">Requirements</label>
	<input type="text" id="" name="custom[kelkoo_field_g]" value="<?= $custom["kelkoo_field_g"] ?>" /><br />


	<label for="custom_kelkoo_Down Speed">Down Speed</label>
	<input type="text" id="" name="custom[kelkoo_field_h]" value="<?= $custom["kelkoo_field_h"] ?>"> kbps<br />


	<label for="custom_kelkoo_Up Speed">Up Speed</label>
	<input type="text" id="" name="custom[kelkoo_field_i]" value="<?= $custom["kelkoo_field_i"] ?>"> kbps<br />


	<label for="custom_kelkoo_Download Cap">Download Cap</label>
	<input type="text" id="" name="custom[kelkoo_field_j]" value="<?= $custom["kelkoo_field_j"] ?>"> GB (0 for none)<br />


	<label for="custom_kelkoo_unique_codes">Unique Codes</label>
	<input type="text" id="" name="custom[kelkoo_field_k]" value="<?= $custom["kelkoo_field_k"] ?>" /><br />
