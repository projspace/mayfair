<?
	/**
	 * e-Commerce System
	 * Copyright (c) 2002-2006 Philip John, All Rights Reserved.
	 * Author	: Philip John
	 * Version	: 6.0
	 *
	 * PROPRIETARY/CONFIDENTIAL.  Use is subject to license terms.
	 */
?>
<h1>Test Rules</h1><hr />
<p>Please enter values for the transaction variables below.  When you are ready, click the "Check" button to run your Shipping Rules.</p>

<form method="post" action="<?= $config['dir']; ?>index.php?fuseaction=admin.testRules&amp;act=check">
<div class="legend">Transaction Variables</div>
<div class="form">
	<label for="country">Country</label>
	<select id="" name="country">
<?
	$area_id=0;
	while($country=$countries->FetchRow())
	{
		echo "<option";
		if($country['id']==$config['defaultcountry_id'])
		{
			echo " selected=\"selected\"";
			$area_id=$country['area_id'];
		}
		echo ">{$country['name']}</option>";
	}
?>
	</select><br />

	<label for="area">Area</label>
	<select id="area" name="area">
<?
	while($area=$areas->FetchRow())
	{
		echo "<option";
		if($area['id']==$area_id)
			echo " selected=\"selected\"";
		echo ">{$area['name']}</option>";
	}
?>
	</select><br />


	<label for="shipping">Shipping</label>
	<input type="text" id="shipping" name="shipping" /><br />

	<label for="total">Total</label>
	<input type="text" id="total" name="total" /><br />

	<label for="nitems">Number of Items</label>
	<input type="text" id="nitems" name="nitems" /><br />

	<label for="weight">Total Weight</label>
	<input type="text" id="weight" name="weight" /><br />

	<label for="coupon">Coupon ID</label>
	<input type="text" id="coupon" name="coupon" /><br />
</div>
<div class="formRight">
	<input class="submit" type="submit" value="Check">
</div />
</form>