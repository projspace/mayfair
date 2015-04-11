<label for="custom_kelkoo_type">Type</label>
<select id="custom_kelkoo_type" name="custom[kelkoo_type]">
<? if(trim($custom["kelkoo_type"])!="") echo "<option>{$custom["kelkoo_type"]}</option>\n"; ?>
	<option>Barebone PC</option>
	<option>Blank Media</option>
	<option>Blank Media Accessory</option>
	<option>Bluetooth</option>
	<option>Cable</option>
	<option>Cabinet</option>
	<option>Computer Case</option>
	<option>Computer Case Accessory</option>
	<option>Consumables</option>
	<option>Controller Card</option>
	<option>Fan</option>
	<option>Graphics Card</option>
	<option>Graphics Tablet</option>
	<option>Headset</option>
	<option>Hub</option>
	<option>Inkjet Cartridge</option>
	<option>Joysticks and Gaming</option>
	<option>Keyboard</option>
	<option>Laptop Bag</option>
	<option>Laser Toner</option>
	<option>Memory Reader</option>
	<option>Microphone</option>
	<option>Mouse</option>
	<option>Modem</option>
	<option>Monitor Accessory</option>
	<option>Motherboard</option>
	<option>Mouse Mat</option>
	<option>Networking</option>
	<option>Other Computer Accessory</option>
	<option>Paper</option>
	<option>Power Supplies</option>
	<option>Printer Accessory</option>
	<option>Processors</option>
	<option>Roller Ball</option>
	<option>Scanner</option>
	<option>Sound Card</option>
	<option>Speakers</option>
	<option>Streaming Media Device</option>
	<option>Tablet</option>
	<option>TV Card</option>
	<option>Video Editing Card</option>
	<option>Warranty</option>
	<option>WebCam</option>
	<option>Wireless Card</option>
	<option>Wireless Adapter</option>
</select><br />

<label for="custom_kelkoo_manufacturer">Manufacturer</label>
<input type="text" id="custom_kelkoo_manufacturer" name="custom[kelkoo_field_c]" value="<?= $custom["kelkoo_field_c"] ?>" /><br />

<label for="custom_kelkoo_model_name">Model Name</label>
<input type="text" id="custom_kelkoo_model_name" name="custom[kelkoo_field_d]" value="<?= $custom["kelkoo_field_d"] ?>" /><br />

<label for="custom_kelkoo_unique_codes">Unique Codes</label>
<input type="text" id="custom_kelkoo_unique_codes" name="custom[kelkoo_field_k]" value="<?= $custom["kelkoo_field_k"] ?>" /><br />