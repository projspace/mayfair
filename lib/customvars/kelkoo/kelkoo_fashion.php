
	<label for="custom_kelkoo_type">Type</label>
	<select id="" name="custom[kelkoo_type]">
	<? if(trim($custom["kelkoo_type"])!="") echo "<option>{$custom["kelkoo_type"]}</option>\n"; ?>
		<optgroup label="Tops">
		<option>blouses</option>
		<option>casual shirts</option>
		<option>formal shirts</option>
		<option>long-sleeve tops</option>
		<option>polo shirts</option>
		<option>rugby shirts</option>
		<option>sweatshirts</option>
		<option>t-shirts</option>
		<option>-Knitwear:</option>
		<option>cardigans</option>
		<option>sweaters</option>
		</optgroup>
		<optgroup label="Trousers/skirts/dresses/shorts">
		<option>casual trousers</option>
		<option>cropped trousers</option>
		<option>dresses</option>
		<option>formal trousers</option>
		<option>jeans</option>
		<option>shorts</option>
		<option>skirts</option>
		<option>suit trousers</option>
		</optgroup>
		<optgroup label="Accessories">
		<option>bags</option>
		<option>belts</option>
		<option>caps</option>
		<option>cufflinks</option>
		<option>gloves</option>
		<option>hats</option>
		<option>purses</option>
		<option>sarongs</option>
		<option>scarves</option>
		<option>sunglasses</option>
		<option>ties</option>
		<option>wallets</option>
		</optgroup>
		<optgroup label="Coats/jackets/suits">
		<option>coats</option>
		<option>jackets</option>
		<option>dress suits</option>
		<option>skirt suits</option>
		<option>trouser suits</option>
		</optgroup>
		<optgroup label="Footwear">
		<option>boots</option>
		<option>casual shoes</option>
		<option>evening shoes</option>
		<option>formal shoes</option>
		<option>sandals</option>
		<option>slippers</option>
		<option>trainers</option>
		</optgroup>
		<optgroup label="Jewellery">
		<option>bracelets</option>
		<option>brooches</option>
		<option>chains</option>
		<option>earrings</option>
		<option>lighters</option>
		<option>necklaces</option>
		<option>pendants</option>
		<option>rings</option>
		<option>watches</option>
		</optgroup>
		<optgroup label="Lingerie/nightwear/underwear">
		<option>basques corsets and bustiers</option>
		<option>bodies</option>
		<option>boxers</option>
		<option>bras</option>
		<option>briefs</option>
		<option>camisoles</option>
		<option>combination sets</option>
		<option>dressing gowns</option>
		<option>loungewear</option>
		<option>pyjamas</option>
		<option>sports socks</option>
		<option>socks</option>
		<option>hosiery</option>
		<option>suspenders and garters</option>
		<option>thermal underwear</option>
		<option>thongs and g-strings</option>
		<option>vests</option>
		</optgroup>
		<optgroup label="Sportswear/swimwear/leisurewear">
		<option>sports dresses and skirts</option>
		<option>sports pants</option>
		<option>sports shorts</option>
		<option>sports sweatshirts and tracktops</option>
		<option>sports tops</option>
		<option>sports tracksuits</option>
		<option>beachwear</option>
		<option>bikinis</option>
		<option>swimsuits</option>
		<option>tankinis</option>
		<option>trunks</option>
		</optgroup>
		<optgroup label="Fancy Dress">
		<option>fancy dress</option>
		</optgroup>
		<optgroup label="Schoolwear (children only)">
		<option>school trousers</option>
		<option>school skirts</option>
		<option>school blazers</option>
		</optgroup>
		<optgroup label="Protective Clothing">
		<option>high visibility clothing</option>
		<option>knee pads</option>
		<option>protective boots</option>
		<option>protective gloves</option>
		<option>protective masks</option>
		<option>safety boots</option>
		<option>utility belts</option>
	</select><br />


	<label for="custom_kelkoo_Brand">Brand</label>
	<input type="text" id="" name="custom[kelkoo_field_c]" value="<?= $custom["kelkoo_field_c"] ?>" /><br />


	<label for="custom_kelkoo_Product Name">Product Name</label>
	<input type="text" id="" name="custom[kelkoo_field_d]" value="<?= $custom["kelkoo_field_d"] ?>" /><br />


	<label for="custom_kelkoo_Size">Size</label>
	<input type="text" id="" name="custom[kelkoo_field_e]" value="<?= $custom["kelkoo_field_e"] ?>" /><br />


	<label for="custom_kelkoo_Department">Department</label>
	<select id="" name="custom[kelkoo_field_f]">
	<? if(trim($custom["kelkoo_field_f"])!="") echo "<option>{$custom["kelkoo_field_f"]}</option>\n"; ?>
		<option>Men</option>
		<option>Women</option>
		<option>Girls</option>
		<option>Boys</option>
		<option>Babies</option>
		<option>Unisex</option>
	</select><br />


	<label for="custom_kelkoo_Colour">Colour</label>
	<input type="text" id="" name="custom[kelkoo_field_g]" value="<?= $custom["kelkoo_field_g"] ?>" /><br />


	<label for="custom_kelkoo_Fabric">Fabric</label>
	<input type="text" id="" name="custom[kelkoo_field_h]" value="<?= $custom["kelkoo_field_h"] ?>" /><br />


	<label for="custom_kelkoo_unique_codes">Unique Codes</label>
	<input type="text" id="" name="custom[kelkoo_field_k]" value="<?= $custom["kelkoo_field_k"] ?>" /><br />
