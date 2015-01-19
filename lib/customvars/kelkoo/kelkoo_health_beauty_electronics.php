
	<label for="custom_kelkoo_type">Type</label>
	<select id="" name="custom[kelkoo_type]">
	<? if(trim($custom["kelkoo_type"])!="") echo "<option>{$custom["kelkoo_type"]}</option>\n"; ?>s
		<option>Bath Spa</option>
		<option>Bathroom Scales</option>
		<option>Blood Pressure Monitor</option>
		<option>Beard Trimmer</option>
		<option>Body Toner</option>
		<option>Electric Shaver</option>
		<option>Electric Toothbrush</option>
		<option>Footspa</option>
		<option>Hair Clippers</option>
		<option>Hairdryer</option>
		<option>Hair Styling</option>
		<option>Massager</option>
		<option>Mirror</option>
		<option>Other Bodycare</option>
		<option>Other Haircare</option>
		<option>Other Healthcare</option>
	</select><br />


	<label for="custom_kelkoo_manufacturer">Manufacturer</label>
	<input type="text" id="" name="custom[kelkoo_field_c]" value="<?= $custom["kelkoo_field_c"] ?>" /><br />


	<label for="custom_kelkoo_Model No.">Model No.</label>
	<input type="text" id="" name="custom[kelkoo_field_d]" value="<?= $custom["kelkoo_field_d"] ?>" /><br />


	<label for="custom_kelkoo_unique_codes">Unique Codes</label>
	<input type="text" id="" name="custom[kelkoo_field_k]" value="<?= $custom["kelkoo_field_k"] ?>" /><br />
