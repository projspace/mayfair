
<label for="custom_kelkoo_type">Type</label>
<select id="" name="custom[kelkoo_type]">
<? if(trim($custom["kelkoo_type"])!="") echo "<option>{$custom["kelkoo_type"]}</option>\n"; ?>
	<option>Windows Software</option>
	<option>Linux Software</option>
	<option>Mac Software</option>
	<option>PALM Software</option>
	<option>OS/2 Software</option>
	<option>Solaris Software</option>
</select><br />


<label for="custom_kelkoo_Publisher">Publisher</label>
<input type="text" id="" name="custom[kelkoo_field_c]" value="<?= $custom["kelkoo_field_c"] ?>" /><br />


<label for="custom_kelkoo_Title">Title</label>
<input type="text" id="" name="custom[kelkoo_field_d]" value="<?= $custom["kelkoo_field_d"] ?>" /><br />


<label for="custom_kelkoo_Cat No.">Cat No.</label>
<input type="text" id="" name="custom[kelkoo_field_k]" value="<?= $custom["kelkoo_field_k"] ?>" /><br />
