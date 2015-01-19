<label for="custom_kelkoo_type">Type</label>
<select id="custom_kelkoo_type" id="" name="custom[kelkoo_type]">
<? if(trim($custom["kelkoo_type"])!="") echo "<option>{$custom["kelkoo_type"]}</option>\n"; ?>
	<option>Laptop</option>
	<option>Desktop</option>
	<option>Server</option>
	<option>Tablet</option>
</select><br />

<label for="custom_kelkoo_manufacturer">Manufacturer</label>
<input type="text" id="custom_kelkoo_manufacturer" id="" name="custom[kelkoo_field_c]" value="<?= $custom["kelkoo_field_c"] ?>" /><br />

<label for="custom_kelkoo_model">Model</label>
<input type="text" id="custom_kelkoo_model" id="" name="custom[kelkoo_field_d]" value="<?= $custom["kelkoo_field_d"] ?>" /><br />

<label for="custom_kelkoo_processor">Processor Type</label>
<input type="text" id="custom_kelkoo_processor" id="" name="custom[kelkoo_field_e]" value="<?= $custom["kelkoo_field_e"] ?>" /><br />

<label for="custom_kelkoo_speed">Processor Speed</label>
<input type="text" id="custom_kelkoo_speed" id="" name="custom[kelkoo_field_f]" value="<?= $custom["kelkoo_field_f"] ?>"> MHz<br />

<label for="custom_kelkoo_hd_size">Hard Disk Size</label>
<input type="text" id="custom_kelkoo_hd_size" id="" name="custom[kelkoo_field_g]" value="<?= $custom["kelkoo_field_g"] ?>"> GB<br />

<label for="custom_kelkoo_memory">Memory</label>
<input type="text" id="custom_kelkoo_memory" id="" name="custom[kelkoo_field_h]" value="<?= $custom["kelkoo_field_h"] ?>"> MB<br />

<label for="custom_kelkoo_monitor_size">Monitor Size</label>
<select id="custom_kelkoo_monitor_size" id="" name="custom[kelkoo_field_i]">
<? if(trim($custom["kelkoo_field_i"])!="") echo "<option>{$custom["kelkoo_field_i"]}</option>\n"; ?>
	<option value="0">No monitor</option>
	<option value="1">Unknown</option>
<?
	for($i=2;$i<48;$i++)
		echo "<option value=\"$i\">$i inches</option>\n";
?>
</select><br />

<label for="custom_kelkoo_unique_codes">Unique Codes</label>
<input type="text" id="custom_kelkoo_unique_codes" id="" name="custom[kelkoo_field_k]" value="<?= $custom["kelkoo_field_k"] ?>" /><br />