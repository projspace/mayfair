<h1>Hamper delivery</h1><br />

<p>You've selected one or more hampers.  We are able to deliver these to alternate addresses as gifts if you wish.</p>
<p>To deliver to another address please enter it against each hamper below and the click on the 'Continue' button.</p>
<p>If several are going to the same destination you may use the 'copy' and 'paste' links below to copy the details from one hamper and paste them in another.</p>
<p>Please be aware that some of our products are restricted in certain countries, the countries you may have the hamper delivered to are listed in the drop-down list on each address segment.</p>

<form method="post" action="{$config.dir}delivery?act=save{$sid_amp}"{validator_form}>
<table class="cart">
	<tr>
		<th>Hamper</th>
		<th>Delivery Address</th>
		<th>&nbsp;</th>
	</tr>
{foreach name=hamper from=$hampers item=hamper}
	{if $smarty.foreach.hamper.iteration is even}
		{assign var=class value="dark"}
	{else}
		{assign var=class value="light"}
	{/if}
	<tr>
		<td class="{$class}bottom">{$hamper.product.name}</td>
		<td class="{$class}bottom form">
			<input type="hidden" name="delivery[{$smarty.foreach.hamper.iteration}][cart_id]" value="{$hamper.product.cart_id}" />
					
			<label for="delivery_name_{$smarty.foreach.hamper.iteration}">Name</label>
			<input type="text" id="delivery_name_{$smarty.foreach.hamper.iteration}" name="delivery[{$smarty.foreach.hamper.iteration}][name]" value="{$hamper.delivery.name}" /><br />
			
			<label for="delivery_address_{$smarty.foreach.hamper.iteration}">Address</label>
			<textarea id="delivery_address_{$smarty.foreach.hamper.iteration}" name="delivery[{$smarty.foreach.hamper.iteration}][address]" rows="5" cols="20">{$hamper.delivery.address}</textarea><br />
			
			<label for="delivery_country_{$smarty.foreach.hamper.iteration}">Country</label>
			<select id="delivery_country_{$smarty.foreach.hamper.iteration}" name="delivery[{$smarty.foreach.hamper.iteration}][country]">
				<option value="">Please Select</option>
				{foreach from=$hamper.countries item=country}
					<option value="{$country.name}"{if $country.name==$hamper.delivery.country} selected="selected"{/if}>{$country.name}</option>
				{/foreach}
			</select><br />
		</td>
		<td class="{$class}bottom"><a onclick="shop_DeliveryCopy({$smarty.foreach.hamper.iteration});">Copy</a><br /><br />
		<a onclick="shop_DeliveryPaste({$smarty.foreach.hamper.iteration})">Paste</a></td>
	</tr>
{/foreach}
</table>
<br />
<div align="right">
	<input type="image" src="{$config.dir}layout/templates/partridges/images/buttons/continue.gif" alt="Continue" title="Continue" />
</div>

</form><br />
