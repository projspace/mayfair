<div class="heading">
	<h1>Payment Details</h1>
</div>
<div class="shopContent">
Please enter your payment details in the form below, all fields with a * next to them are compulsory.
</div>
<div class="shopContent">
<form id="details" name="details" method="post" action="{$config.dir}index.php/fuseaction/shop.checkout/act/saveDetails">
	<input type="hidden" name="country_id" value="{$params.country_id}">

	<label for="name">Cardholders Name *</label>
	<input type="text" id="name" name="name" value="{$params.request.name}" /><br />

	<label for="address">Billing Address *</label>
	<textarea id="address" name="address" rows="5" cols="40">{$params.request.address}</textarea><br />

	<label for="postcode">Billing Postcode *</label>
	<input type="text" id="postcode" name="postcode" value="{$params.request.postcode}" /><br />

	<label for="country">Billing Country *</label>
	<input type="text" id="country" name="country" value="{$params.request.country}" /><br /><br />

	<label for="deliver_billing">Deliver to billing?</label>
	<input class="checkbox" type="checkbox" name="deliver_billing" id="deliver_billing" onchange="if(this.checked) {ldelim}
		document.getElementById('delivery_name').disabled=true;
		document.getElementById('delivery_address').disabled=true;
    	document.getElementById('delivery_postcode').disabled=true;
	{rdelim} else {ldelim}
		document.getElementById('delivery_name').disabled=false;
		document.getElementById('delivery_address').disabled=false;
		document.getElementById('delivery_postcode').disabled=false;
	{rdelim};"{if $params.request.deliver_billing=="on"} checked{/if}><br />

	<label for="name">Delivery Name *</label>
	<input {if $params.request.deliver_billing=="on"}disabled="disabled" {/if}type="text" id="delivery_name" name="delivery_name" value="{$params.request.delivery_name}" /><br />

    <label for="delivery_address">Delivery Address *</label>
    <textarea {if $params.request.deliver_billing=="on"}disabled="disabled" {/if}id="delivery_address" name="delivery_address" rows="5" cols="40">{$params.request.delivery_address}</textarea><br />

    <label for="delivery_postcode">Delivery Postcode *</label>
    <input {if $params.request.deliver_billing=="on"}disabled="disabled" {/if}type="text" id="delivery_postcode" name="delivery_postcode" value="{$params.request.delivery_postcode}" /><br />

    <label for="delivery_country">Delivery Country</label>
    <input type="hidden" name="delivery_country" value="{$params.vars.country}" /><span>{$params.vars.country}</span><br /><br />

	<label for="email">Email *</label>
	<input type="text" id="email" name="email" value="{$params.request.email}" /><br />

	<label for="tel">Telephone</label>
	<input type="text" id="tel" name="tel" value="{$params.request.tel}" /><br /><br />

	<label for="card_type">Card Type *</label>
	<select name="card_type" id="card_type">
		{if $params.request.card_type!=""}
		<option>{$params.request.card_type}</option>
		{/if}
		<option>Visa</option>
		<option>Mastercard</option>
		<option>Delta</option>
		<option>Switch</option>
		<option>Solo</option>
	</select><br />

	<label for="card_no">Card No. *</label>
	<input type="text" id="card_no" name="card_no" value="{$params.request.card_no}" /><br />

	<label for="card_cv2">CV2 Code</label>
	<input type="text" id="card_cv2" name="card_cv2" size="3" value="{$params.request.card_cv2}" />
	<span><i>(last 3 digits on back of card)</i></span><br />

	<label for="card_start">Start Date (MM/YY)</label>
	<input type="text" id="card_start" name="card_start" value="{$params.request.card_start}" /><br />

	<label for="card_end">Expiry Date (MM/YY) *</label>
	<input type="text" id="card_end" name="card_end" value="{$params.request.card_end}" /><br />

	<label for="card_issue">Issue No. (switch only)</label>
	<input type="text" id="card_issue" name="card_issue" size="1" value="{$params.request.card_issue}" /><br /><br />

	<input style="float: left;" type="checkbox" id="terms" name="terms"{if $params.request.terms=="on"} checked="checked"{/if} />
	<label style="width: auto;">I accept the <a href="{$config.dir}termsandconditions" target="_new">Terms and Conditions</a> of sale</label><br />

	<div class="right">
		<input class="submit" type="Submit" value="Finish" />
	</div>

	</form>
</div>
