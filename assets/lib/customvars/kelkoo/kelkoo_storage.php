<label for="custom_kelkoo_type">Type</label>
<select id="custom_kelkoo_type" name="custom[kelkoo_type]">
<? if(trim($custom["kelkoo_type"])!="") echo "<option>{$custom["kelkoo_type"]}</option>\n"; ?>
	<option>Hard Drive</option>
	<option>CD Writer</option>
	<option>Zip Drive</option>
	<option>Floppy Drive</option>
	<option>Tape Drive</option>
	<option>DAT Drive</option>
	<option>Optical Drive</option>
	<option>Jazz Drive</option>
	<option>DVD Drive</option>
	<option>DVD Writer</option>
	<option>CD Drive</option>
	<option>Pen Drive</option>
</select><br />

<label for="custom_kelkoo_manufacturer">Manufacturer</label>
<input type="text" id="custom_kelkoo_manufacturer" name="custom[kelkoo_field_c]" value="<?= $custom["kelkoo_field_c"] ?>" /><br />

<label for="custom_kelkoo_model_name">Model Name</label>
<input type="text" id="custom_kelkoo_model_name" name="custom[kelkoo_field_d]" value="<?= $custom["kelkoo_field_d"] ?>" /><br />

<label for="custom_kelkoo_storage_size">Storage Size</label>
<input type="text" id="custom_kelkoo_storage_size" name="custom[kelkoo_field_e]" value="<?= $custom["kelkoo_field_e"] ?>"> (MB or GB)<br />

<label for="custom_kelkoo_Interface">Interface</label>
<select id="custom_kelkoo_Interface" name="custom[kelkoo_field_f]">
<? if(trim($custom["kelkoo_field_f"])!="") echo "<option>{$custom["kelkoo_field_f"]}</option>\n"; ?>
	<option>ATA</option>
	<option>EIDE</option>
	<option>Fibre Channel</option>
	<option>FireWire</option>
	<option>IDE</option>
	<option>PC Card</option>
	<option>SCSI</option>
	<option>Serial ATA</option>
	<option>USB</option>
	<option>USB2</option>
</select><br />

<label for="custom_kelkoo_unique_codes">Unique Codes</label>
<input type="text" id="custom_kelkoo_unique_codes" name="custom[kelkoo_field_k]" value="<?= $custom["kelkoo_field_k"] ?>" /><br />