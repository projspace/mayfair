<label for="custom_kelkoo_type">Type</label>
<select id="" name="custom[kelkoo_type]">
<? if(trim($custom["kelkoo_type"])!="") echo "<option>{$custom["kelkoo_type"]}</option>\n"; ?>
	<option>PC</option>
	<option>Mac</option>
	<option>PSTN 2</option>
	<option>PSP</option>
	<option>Playstation</option>
	<option>Nintendo GameCube</option>
	<option>Nintendo GameBoy</option>
	<option>Nintendo DS</option>
	<option>Gameboy Advance</option>
	<option>Microsoft Xbox</option>
	<option>Microsoft Xbox 360</option>
</select><br />

<label for="custom_kelkoo_publisher">Publisher</label>
<input type="text" id="custom_kelkoo_publisher" name="custom[kelkoo_field_c]" value="<?= $custom["kelkoo_field_c"] ?>" /><br />

<label for="custom_kelkoo_title">Title</label>
<input type="text" id="custom_kelkoo_title" name="custom[kelkoo_field_d]" value="<?= $custom["kelkoo_field_d"] ?>" /><br />

<label for="custom_kelkoo_cat_no">Cat No.</label>
<input type="text" id="custom_kelkoo_cat_no" name="custom[kelkoo_field_k]" value="<?= $custom["kelkoo_field_k"] ?>" /><br />