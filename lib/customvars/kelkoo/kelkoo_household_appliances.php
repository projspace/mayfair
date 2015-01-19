
	<label for="custom_kelkoo_type">Type</label>
	<select id="" name="custom[kelkoo_type]">
	<? if(trim($custom["kelkoo_type"])!="") echo "<option>{$custom["kelkoo_type"]}</option>\n"; ?>
		<option>Blender</option>
		<option>Bread Maker</option>
		<option>Can Opener</option>
		<option>Chiller</option>
		<option>Clock</option>
		<option>Coffee Grinder</option>
		<option>Coffee Maker</option>
		<option>Cooker</option>
		<option>Dishwasher</option>
		<option>Dryer</option>
		<option>Electric Carving Knife</option>
		<option>Food Processor</option>
		<option>Freezer</option>
		<option>Fridge</option>
		<option>Fridge / Freezer</option>
		<option>Fryer</option>
		<option>Grill</option>
		<option>Hob</option>
		<option>Hood</option>
		<option>Ice Cream Maker</option>
		<option>Iron</option>
		<option>Ironing Board</option>
		<option>Juicer</option>
		<option>Kettle</option>
		<option>Kitchen Scales</option>
		<option>Microwave</option>
		<option>Mixer</option>
		<option>Other Household</option>
		<option>Oven</option>
		<option>Sandwich Maker</option>
		<option>Sewing Machine</option>
		<option>Slow Cooker</option>
		<option>Small Kitchen</option>
		<option>Steamer</option>
		<option>Toaster</option>
		<option>Trouser Press</option>
		<option>Vacuum Cleaner</option>
		<option>Washer / Dryer</option>
		<option>Washing Machine</option>
	</select><br />


	<label for="custom_kelkoo_manufacturer">Manufacturer</label>
	<input type="text" id="" name="custom[kelkoo_field_c]" value="<?= $custom["kelkoo_field_c"] ?>" /><br />


	<label for="custom_kelkoo_Model No.">Model No.</label>
	<input type="text" id="" name="custom[kelkoo_field_d]" value="<?= $custom["kelkoo_field_d"] ?>" /><br />


	<label for="custom_kelkoo_unique_codes">Unique Codes</label>
	<input type="text" id="" name="custom[kelkoo_field_k]" value="<?= $custom["kelkoo_field_k"] ?>" /><br />
