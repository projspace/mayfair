<div class="heading">
	<h1>Checkout : Order details</h1>
</div>

<div class="shopContent">
	<p>You are now in the checkout section of {$config.company}.</p>
	<p>Please check over your shopping cart to ensure that your order is correct, changing items if necessary. Once you are happy, please click on the 'Continue' button at the bottom of the page to be taken to our Payment Service Provider <!--({$config.psp.name})-->.</p>
	<p>All transactions through {$config.psp.name} are secured by industry-standard 128-bit SSL encryption to ensure that you card details remain secret at all times.</p>
</div>

<table class="cart">
	<tr>
		<th>Product</th>
		<th align="right">Price</th>
		<th align="right">Quantity</th>
		<th>&nbsp;</th>
		<th align="right">Total</th>
		<th>&nbsp;</th>
	</tr>

	{foreach from=$cart item=item name=test}
	{if $smarty.foreach.test.iteration is even}
		{assign var=class value="dark"}
	{else}
		{assign var=class value="light"}
	{/if}
	<tr>
		<td class="{$class}"><strong>{$item.brand_name} {$item.name}</strong></td>
		<td align="right" class="{$class}">${$item.cart_price-$item.cart_discount|price}</td>
		<td align="right" class="{$class}">{$item.cart_quantity}</td>
		<td class="{$class}" width="32"><form method="post" action="{$config.dir}checkout?act=increase" class="buttons">
				{$sid_form}
				<input type="hidden" name="country_id" value="{$country_id}" />
				<input type="hidden" name="cartid" value="{$item.cart_id}" />
				<input type="image" src="{$config.dir}images/plus.png" alt="+" title="+" width="16" height="12" />
			</form><form method="post" action="{$config.dir}checkout?act=decrease" class="buttons">
				{$sid_form}
				<input type="hidden" name="country_id" value="{$country_id}" />
				<input type="hidden" name="cartid" value="{$item.cart_id}" />
				<input type="image" src="{$config.dir}images/minus.png" alt="-" title="-" width="16" height="12" />
			</form></td>
		<td align="right" class="{$class}"><strong>${$item.total|price}</strong></td>
		<td class="{$class}"><div class="action">
			<form method="post" action="{$config.dir}checkout?act=remove" onsubmit="return shop_FormConfAct(1,'remove','product','from your cart');">
				{$sid_form}
				<input type="hidden" name="country_id" value="{$country_id}" />
				<input type="hidden" name="cartid" value="{$item.cart_id}" />
				<input type="image" src="{$config.dir}layout/templates/partridges/images/buttons/removeitem.gif" width="84" height="16" alt="Remove Item" title="Remove Item" />
			</form></div>
		</td>
	</tr>
	<tr>
		<td colspan="6" class="{$class}bottom">
			<form method="get" action="{$config.dir}checkout">
			{$sid_form}
			<input type="hidden" name="act" value="update" />
			<input type="hidden" name="country_id" value="{$country_id}" />
			<input type="hidden" name="cartid" value="{$item.cart_id}" />
			{if $item.options}
			{assign var=count value=0}
			{foreach from=$item.options item=option}
			<label>{$option.name}</label>
			<select name="option[]" onchange="submit();">
				{shop_options
					value=$option.value
					price=$option.price
					base=$item.price
					selected=$item.cart_options.$count
				}
			</select><br />
			{assign var=count value=$count+1}
			{/foreach}
			{/if}
			</form>
		</td>
	</tr>
	{/foreach}

	<tr>
		<th colspan="4" align="right">Subtotal</th>
		<th align="right">&nbsp;${$details.total|price}</th>
		<th>&nbsp;</th>
	</tr>
	<tr>
		<th colspan="6" align="right"><form method="get" action="{$config.dir}checkout" class="country">
				{$sid_form}
				<select name="country_id" id="country_id" onchange="submit();">
					{country_options values=$countries selected=$country_id}
				</select>
				<label for="country_id">Country</label>
			</form>
		</th>
	</tr>
	<tr>
		<th colspan="4" align="right">Packing</th>
		<th><div class="numerical">&nbsp;${$details.packing|price}</div></th>
		<th>&nbsp;</th>
	</tr>
	<tr>
		<th colspan="4" align="right">Shipping</th>
		<th><div class="numerical">&nbsp;${$details.shipping|price}</div></th>
		<th>&nbsp;</th>
	</tr>
	<tr>
		<th colspan="4" align="right">Total</th>
		<th><div class="numerical">&nbsp;${$details.total+$details.shipping+$details.packing|price}</div></th>
		<th>&nbsp;</th>
	</tr>
</table>
