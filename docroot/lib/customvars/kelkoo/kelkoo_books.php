<label for="custom_kelkoo_type">Type</label>
<select id="" name="custom[kelkoo_type]">
<? if(trim($custom["kelkoo_type"])!="") echo "<option>{$custom["kelkoo_type"]}</option>\n"; ?>
	<option>Hardback</option>
	<option>Paperback</option>
	<option>Audio Books</option>
</select><br />

<label for="custom_kelkoo_Title">Title</label>
<input type="text" id="" name="custom[kelkoo_field_c]" value="<?= $custom["kelkoo_field_c"] ?>" /><br />

<label for="custom_kelkoo_Author">Author</label>
<input type="text" id="" name="custom[kelkoo_field_d]" value="<?= $custom["kelkoo_field_d"] ?>" /><br />

<label for="custom_kelkoo_ISBN">ISBN</label>
<input type="text" id="" name="custom[kelkoo_field_k]" value="<?= $custom["kelkoo_field_k"] ?>" /><br />