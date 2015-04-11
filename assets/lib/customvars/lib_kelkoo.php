<?
	/**
	 * e-Commerce System Custom Fields Plugin
	 * Copyright (c) 2002-2006 Philip John, All Rights Reserved.
	 * Author	: Philip John
	 * Version	: 6.0
	 *
	 * PROPRIETARY/CONFIDENTIAL.  Use is subject to license terms.
	 */
?>
<script type="text/javascript" src="<?= $config["dir"] ?>lib/lib_XMLHTTPRequest.js"></script>
<script type="text/javascript" src="<?= $config["dir"] ?>lib/customvars/kelkoo/lib_KelkooHandler.js"></script>

<div class="legend">Kelkoo</div>
<div class="form">
	<label for="custom_kelkoo_category">Category</label>
	<select id="custom_kelkoo_category" name="custom[kelkoo_category]" onchange="kelkoo(this.value,<?
	if(count($custom)>0)
		echo "'".urlencode(serialize($custom))."'";
	else
		echo "''";
?>);">
<?
	if(count($custom)>0)
	{
		echo "<option value=\"".str_replace("&","[AND]",$custom["kelkoo_category"])."\">{$custom["kelkoo_category"]}</option>";
		echo "<script language=\"JavaScript\" type=\"text/javascript\">
	kelkoo('{$custom["kelkoo_category"]}','".urlencode(serialize($custom))."');
		</script>";
	}
?>
		<option value="0">--- Select one ---</option>
		<option value="Computers">Computers</option>
		<option value="Computer Accessories">Computer Accessories</option>
		<option value="Memory">Memory</option>
		<option value="Monitors">Monitors</option>
		<option value="PDA">PDA</option>
		<option value="Printers">Printers</option>
		<option value="Software">Software</option>
		<option value="Storage">Storage</option>
		<option value="ISPs">ISPs</option>
		<option value="Electronics">Electronics</option>
		<option value="Health [AND] Beauty Electronics">Health &amp; Beauty Electronics</option>
		<option value="Household Appliances">Household Appliances</option>
		<option value="In-Car Entertainment">In-Car Entertainment</option>
		<option value="Mobile Phones">Mobile Phones</option>
		<option value="Communication">Communication</option>
		<option value="Books">Books</option>
		<option value="Consoles">Consoles</option>
		<option value="Films/Genre">Films/Genre</option>
		<option value="Music Downloads">Music Downloads</option>
		<option value="Video Games">Video Games</option>
		<option value="Chocolate">Chocolate</option>
		<option value="Fashion">Fashion</option>
		<option value="Flowers">Flowers</option>
		<option value="Gadgets">Gadgets</option>
		<option value="Home [AND] Garden">Home &amp; Garden</option>
		<option value="Musical Instruments">Musical Instruments</option>
		<option value="Office Supplies">Office Supplies</option>
		<option value="Perfume">Perfume</option>
		<option value="Sport">Sport</option>
		<option value="Toys">Toys</option>
		<option value="Wine">Wine</option>
		<option value="Property">Property</option>
		<option value="Car Parts">Car Parts</option>
	</select><br />
	<div id="kelkoo"></div>
</div>