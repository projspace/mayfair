
	<label for="custom_kelkoo_type">Type</label>
	<select id="" name="custom[kelkoo_type]">
	<? if(trim($custom["kelkoo_type"])!="") echo "<option>{$custom["kelkoo_type"]}</option>\n"; ?>
		<option>Accessories</option>
		<option>Amplifiers</option>
		<option>Cassette Players</option>
		<option>CD Multiplayers</option>
		<option>CD Players</option>
		<option>Graphic Equalisers</option>
		<option>Mini-Disc Players</option>
		<option>Multimedia</option>
		<option>Packages</option>
		<option>Radar Detectors</option>
		<option>Speakers</option>
		<option>Vehicle Security</option>
	</select><br />


	<label for="custom_kelkoo_manufacturer">Manufacturer</label>
	<input type="text" id="" name="custom[kelkoo_field_c]" value="<?= $custom["kelkoo_field_c"] ?>" /><br />


	<label for="custom_kelkoo_Model No">Model No</label>
	<input type="text" id="" name="custom[kelkoo_field_d]" value="<?= $custom["kelkoo_field_d"] ?>" /><br />


	<label for="custom_kelkoo_unique_codes">Unique Codes</label>
	<input type="text" id="" name="custom[kelkoo_field_k]" value="<?= $custom["kelkoo_field_k"] ?>" /><br />
