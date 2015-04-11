
	<label for="custom_kelkoo_type">Type</label>
	<select id="" name="custom[kelkoo_type]">
	<? if(trim($custom["kelkoo_type"])!="") echo "<option>{$custom["kelkoo_type"]}</option>\n"; ?>
		<option>Battery</option>
		<option>Cable</option>
		<option>Camcorder / Analogue</option>
		<option>Camcorder / Digital</option>
		<option>Camera / Analogue</option>
		<option>Camera / Digital</option>
		<option>Clock Radio</option>
		<option>DVD Player</option>
		<option>DVD Player / VCR</option>
		<option>GPS</option>
		<option>Headphones</option>
		<option>Hi-Fi System</option>
		<option>Home Cinema</option>
		<option>Microphone</option>
		<option>Other Consumer</option>
		<option>Portable / Cassette Player</option>
		<option>Portable / CD Player</option>
		<option>Portable / MiniDisc</option>
		<option>Portable / MP3 Player</option>
		<option>Portable / Radio</option>
		<option>Portable / Stereo</option>
		<option>Projector</option>
		<option>Remote Control</option>
		<option>Separates / Amplifier</option>
		<option>Separates / Cassette Player</option>
		<option>Separates / CD Player</option>
		<option>Separates / MiniDisc</option>
		<option>Separates / MP3 Player</option>
		<option>Separates / Receiver</option>
		<option>Separates / Tuner</option>
		<option>Set Top Box</option>
		<option>Speakers</option>
		<option>Television / DVD Player</option>
		<option>Television / DVD Player / VCR</option>
		<option>Television / LCD</option>
		<option>Television / Plasma</option>
		<option>Television / Projection</option>
		<option>Television / Standard</option>
		<option>Television / VCR</option>
		<option>Television / Widescreen</option>
		<option>Turntable</option>
		<option>VCR</option>
	</select><br />


	<label for="custom_kelkoo_manufacturer">Manufacturer</label>
	<input type="text" id="" name="custom[kelkoo_field_c]" value="<?= $custom["kelkoo_field_c"] ?>" /><br />


	<label for="custom_kelkoo_Model No.">Model No.</label>
	<input type="text" id="" name="custom[kelkoo_field_d]" value="<?= $custom["kelkoo_field_d"] ?>" /><br />


	<label for="custom_kelkoo_unique_codes">Unique Codes</label>
	<input type="text" id="" name="custom[kelkoo_field_k]" value="<?= $custom["kelkoo_field_k"] ?>" /><br />
