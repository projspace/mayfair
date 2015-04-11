
	<label for="custom_kelkoo_type">Type</label>
	<select id="" name="custom[kelkoo_type]">
	<? if(trim($custom["kelkoo_type"])!="") echo "<option>{$custom["kelkoo_type"]}</option>\n"; ?>
		<option>PC</option>
		<option>Mac</option>
		<option>PS2</option>
		<option>Sony Playstation</option>
		<option>Nintendo GameCube</option>
		<option>Nintendo GameBoy</option>
		<option>Gameboy Advance</option>
		<option>Microsoft XBox</option>
		<option>Gameboy Advance SP</option>
	</select><br />


	<label for="custom_kelkoo_manufacturer">Manufacturer</label>
	<input type="text" id="" name="custom[kelkoo_field_c]" value="<?= $custom["kelkoo_field_c"] ?>" /><br />


	<label for="custom_kelkoo_Model No.">Model No.</label>
	<input type="text" id="" name="custom[kelkoo_field_d]" value="<?= $custom["kelkoo_field_d"] ?>" /><br />


	<label for="custom_kelkoo_Which Platform">Which Platform</label>
	<input type="text" id="" name="custom[kelkoo_field_e]" value="<?= $custom["kelkoo_field_e"] ?>" /><br />


	<label for="custom_kelkoo_unique_codes">Unique Codes</label>
	<input type="text" id="" name="custom[kelkoo_field_k]" value="<?= $custom["kelkoo_field_k"] ?>" /><br />
