<div class="heading">
	<h1>Payment Details</h1>
</div>
<div class="shopContent">
Please enter your payment details in the form below, all fields with a * next to them are compulsory.
</div>
<div class="shopContent">
<form id="details" name="details" method="post" action="{$config.dir}index.php/fuseaction/shop.callback">
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
	<input type="text" id="tel" name="tel" value="{$params.request.tel}" /><br /><br 

	<div class="right">
		<input class="submit" type="Submit" value="Finish" />
	</div>

	</form>
</div>
