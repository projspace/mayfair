
	<label for="custom_kelkoo_type">Type</label>
	<select id="" name="custom[kelkoo_type]">
	<? if(trim($custom["kelkoo_type"])!="") echo "<option>{$custom["kelkoo_type"]}</option>\n"; ?>
		<option>Prepay</option>
		<option>Contract</option>
		<option>Simfree</option>
	</select><br />


	<label for="custom_kelkoo_manufacturer">Manufacturer</label>
	<input type="text" id="" name="custom[kelkoo_field_c]" value="<?= $custom["kelkoo_field_c"] ?>" /><br />


	<label for="custom_kelkoo_Model No.">Model No.</label>
	<input type="text" id="" name="custom[kelkoo_field_d]" value="<?= $custom["kelkoo_field_d"] ?>" /><br />


	<label for="custom_kelkoo_Network">Network</label>
	<input type="text" id="" name="custom[kelkoo_field_e]" value="<?= $custom["kelkoo_field_e"] ?>" /><br />


	<label for="custom_kelkoo_Tarrif Name">Tarrif Name</label>
	<input type="text" id="" name="custom[kelkoo_field_f]" value="<?= $custom["kelkoo_field_f"] ?>" /><br />


	<label for="custom_kelkoo_Line Rental">Line Rental</label>
	�<input type="text" id="" name="custom[kelkoo_field_g]" value="<?= $custom["kelkoo_field_g"] ?>" /><br />


	<label for="custom_kelkoo_Total contract cost (inc vat)">Total contract cost (inc vat)</label>
	�<input type="text" id="" name="custom[kelkoo_field_h]" value="<?= $custom["kelkoo_field_h"] ?>" /><br />


	<label for="custom_kelkoo_Cost Breakdown">Cost Breakdown</label>
	<input type="text" id="" name="custom[kelkoo_field_i]" value="<?= $custom["kelkoo_field_i"] ?>" /><br />


	<label for="custom_kelkoo_Contract Length">Contract Length</label>
	<input type="text" id="" name="custom[kelkoo_field_j]" value="<?= $custom["kelkoo_field_j"] ?>" /><br />


	<label for="custom_kelkoo_unique_codes">Unique Codes</label>
	<input type="text" id="" name="custom[kelkoo_field_k]" value="<?= $custom["kelkoo_field_k"] ?>" /><br />
