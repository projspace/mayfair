
	<label for="custom_kelkoo_type">Type</label>
	<select id="" name="custom[kelkoo_type]">
		<? if(trim($custom["kelkoo_type"])!="") echo "<option>{$custom["kelkoo_type"]}</option>\n"; ?>
		<option>Accessories</option>
		<option>Baby / Toddler</option>
		<option>Bathroom / Plumbing</option>
		<option>Building Materials</option>
		<option>Electrical / Lighting</option>
		<option>Flooring / Laminated</option>
		<option>Flooring / Wooden</option>
		<option>Flooring / Carpets</option>
		<option>Flooring / Rugs</option>
		<option>Flooring / Tiles</option>
		<option>Flooring / Mats</option>
		<option>Flooring / Cork Tiles</option>
		<option>Flooring / Vinyl Tiles</option>
		<option>Flooring / Other Flooring</option>
		<option>Flooring / Accessories</option>
		<option>Food</option>
		<option>Furniture / Beds</option>
		<option>Furniture / Chairs</option>
		<option>Furniture / Accessories</option>
		<option>Furniture / Sofas</option>
		<option>Furniture / Storage</option>
		<option>Furniture / Tables / Desks</option>
		<option>Furniture / Other</option>
		<option>Garden / Barbecues</option>
		<option>Garden / Sheds</option>
		<option>Garden / Furniture</option>
		<option>Garden / Hand Tools</option>
		<option>Garden / Power Tools</option>
		<option>Garden / Lawnmowers</option>
		<option>Garden / Heating</option>
		<option>Garden / Lighting</option>
		<option>Garden / Plants</option>
		<option>Garden / Plant Care</option>
		<option>Garden / Pressure Washers</option>
		<option>Garden / Other</option>
		<option>Hand Tools</option>
		<option>Heating / Cooling</option>
		<option>Luggage</option>
		<option>Painting / Decorating</option>
		<option>Pets</option>
		<option>Posters and Art</option>
		<option>Power Tools / Circular Saws</option>
		<option>Power Tools / Jigsaws</option>
		<option>Power Tools / Saws Accessories</option>
		<option>Power Tools / Corded Drills</option>
		<option>Power Tools / Cordless Drills</option>
		<option>Power Tools / Drill Accessories</option>
		<option>Power Tools / Sanders</option>
		<option>Power Tools / Sander Accessories</option>
		<option>Power Tools / Screwdrivers</option>
		<option>Power Tools / Screwdriver Accessories</option>
		<option>Power Tools / Angle Grinders</option>
		<option>Power Tools / Routers</option>
		<option>Power Tools / Planers</option>
		<option>Power Tools / Other Power Tools</option>
		<option>Security / Alarms</option>
		<option>Security / CCTV</option>
		<option>Security / Child Safety</option>
		<option>Security / Fire Safety</option>
		<option>Security / Locks</option>
		<option>Security / Safes</option>
		<option>Security / Other Security Safety</option>
	</select><br />


	<label for="custom_kelkoo_manufacturer">Manufacturer</label>
	<input type="text" id="" name="custom[kelkoo_field_c]" value="<?= $custom["kelkoo_field_c"] ?>" /><br />


	<label for="custom_kelkoo_model_name">Model Name</label>
	<input type="text" id="" name="custom[kelkoo_field_d]" value="<?= $custom["kelkoo_field_d"] ?>" /><br />


	<label for="custom_kelkoo_unique_codes">Unique Codes</label>
	<input type="text" id="" name="custom[kelkoo_field_k]" value="<?= $custom["kelkoo_field_k"] ?>" /><br />
