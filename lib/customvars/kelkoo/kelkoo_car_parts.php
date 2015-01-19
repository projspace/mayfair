
	<label for="custom_kelkoo_type">Type</label>
	<select id="" name="custom[kelkoo_type]">
	<? if(trim($custom["kelkoo_type"])!="") echo "<option>{$custom["kelkoo_type"]}</option>\n"; ?>
		<option>Braking</option>
		<option>Cooling</option>
		<option>Engine Parts</option>
		<option>Engine Gaskets</option>
		<option>Other electrical</option>
		<option>Steering parts</option>
		<option>Suspension parts</option>
		<option>Transmission parts</option>
		<option>Hydraulics</option>
		<option>Exhausts</option>
		<option>Body parts exterior</option>
		<option>Body parts ancillary</option>
		<option>Lighting</option>
		<option>Lubricants & coolants</option>
		<option>Tools</option>
		<option>Tyres</option>
		<option>Wheels</option>
		<option>Car graphics</option>
		<option>Interior styling</option>
	</select><br />


	<label for="custom_kelkoo_manufacturer">Manufacturer</label>
	<input type="text" id="" name="custom[kelkoo_field_c]" value="<?= $custom["kelkoo_field_c"] ?>" /><br />


	<label for="custom_kelkoo_Name/Number">Name/Number</label>
	<input type="text" id="" name="custom[kelkoo_field_d]" value="<?= $custom["kelkoo_field_d"] ?>" /><br />


	<label for="custom_kelkoo_Make">Make</label>
	<input type="text" id="" name="custom[kelkoo_field_e]" value="<?= $custom["kelkoo_field_e"] ?>" /><br />


	<label for="custom_kelkoo_Model">Model</label>
	<input type="text" id="" name="custom[kelkoo_field_f]" value="<?= $custom["kelkoo_field_f"] ?>" /><br />


	<label for="custom_kelkoo_unique_codes">Unique Codes</label>
	<input type="text" id="" name="custom[kelkoo_field_k]" value="<?= $custom["kelkoo_field_k"] ?>" /><br />
