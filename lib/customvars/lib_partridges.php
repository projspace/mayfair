<div class="legend">Icons</div>
<div class="form">
<?
	$custom=unserialize($row['custom']);
?>
	<label for="organic">Organic</label>
	<input type="checkbox" id="organic" name="custom[icons][organic]"<? if($custom['icons']['organic']=="on") echo " checked=\"checked\""; ?> /><br /><br />

	<label for="diabetic">Diabetic</label>
	<input type="checkbox" id="diabetic" name="custom[icons][diabetic]"<? if($custom['icons']['diabetic']=="on") echo " checked=\"checked\""; ?> /><br /><br />

	<label for="vegetarian">Vegetarian</label>
	<input type="checkbox" id="vegetarian" name="custom[icons][vegetarian]"<? if($custom['icons']['vegetarian']=="on") echo " checked=\"checked\""; ?> /><br /><br />

	<label for="kosher">Kosher</label>
	<input type="checkbox" id="kosher" name="custom[icons][kosher]"<? if($custom['icons']['kosher']=="on") echo " checked=\"checked\""; ?> /><br /><br />

	<label for="nuts">Contains Nuts</label>
	<input type="checkbox" id="nuts" name="custom[icons][nuts]"<? if($custom['icons']['nuts']=="on") echo " checked=\"checked\""; ?> /><br /><br />

	<label for="new">New Product</label>
	<input type="checkbox" id="new" name="custom[icons][new]"<? if($custom['icons']['new']=="on") echo " checked=\"checked\""; ?> /><br /><br />
</div>

<div class="legend">Hampers</div>
<div class="form">
	<label for="organic">Is a hamper?</label>
	<input type="checkbox" id="organic" name="custom[hamper][ishamper]"<? if($custom['hamper']['ishamper']=="on") echo " checked=\"checked\""; ?> /><br /><br />
	
	<label for="ukshipping">UK Shipping Paid?</label>
	<input type="checkbox" id="ukshipping" name="custom[hamper][ukshipping]"<? if($custom['hamper']['ukshipping']=="on") echo " checked=\"checked\""; ?> /><br /><br />
</div>