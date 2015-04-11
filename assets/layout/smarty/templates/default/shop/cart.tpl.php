<div class="heading">
	<h1>Shopping Cart</h1>
</div>
<table class="cart">
	<tr>
		<th>Product</th>
		<th align="right">Price</th>
		<th align="right">Quantity</th>
		<th>&nbsp;</th>
		<th align="right">Total</th>
		<th><div class="action">
			<form method="post" action="{$config.dir}clear" onsubmit="return shop_FormConfAct(2,'remove all items','cart')">
				{$sid_form}
				<input type="hidden" category_id="{$category_id}">
				<input type="image" src="{$config.dir}images/clearall.png" width="82" height="22" alt="Clear All" title="Clear All" />
			</form>
		</div></th>
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
		<td class="{$class}" width="32"><form method="post" action="{$config.dir}increase" class="buttons">
				{$sid_form}
				<input type="hidden" name="country_id" value="{$country_id}" />
				<input type="hidden" name="cartid" value="{$item.cart_id}" />
				<input type="image" src="{$config.dir}images/plus.png" alt="+" title="+" width="16" height="12" />
			</form><form method="post" action="{$config.dir}decrease" class="buttons">
				{$sid_form}
				<input type="hidden" name="country_id" value="{$country_id}" />
				<input type="hidden" name="cartid" value="{$item.cart_id}" />
				<input type="image" src="{$config.dir}images/minus.png" alt="-" title="-" width="16" height="12" />
			</form></td>
		<td align="right" class="{$class}"><strong>${$item.total|price}</strong></td>
		<td class="{$class}"><div class="action">
			<form method="post" action="{$config.dir}remove" onsubmit="return shop_FormConfAct(1,'remove','product','from your cart');">
				{$sid_form}
				<input type="hidden" name="category_id" value="{$category_id}" />
				<input type="hidden" name="cartid" value="{$item.cart_id}" />
				<input type="image" src="{$config.dir}images/removeitem.png" width="82" height="22" alt="Remove Item" title="Remove Item" />
			</form></div>
		</td>
	</tr>
	<tr>
		<td colspan="6" class="{$class}bottom">
			<form id="itemForm{$item.cart_id}" method="get" action="{$config.dir}update">
			{$sid_form}
			<input type="hidden" name="category_id" value="{$category_id}" />
			<input type="hidden" name="cartid" value="{$item.cart_id}" />
			{assign var=count value=0}
			{if $item.options}
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
		<th colspan="4" align="right">Total</th>
		<th align="right">${$details.total|price}</th>
		<th>&nbsp;</th>
	</tr>
</table>
<div class="cartfooter">
	<form method="post" action="{$config.dir}checkout">
		{$sid_form}
		<a href="{$config.dir}{if $last_category_id>0}index.php/fuseaction/shop.category/category_id/{$last_category_id}{/if}"><img src="{$config.dir}images/continueshopping.gif" width="89" height="22" alt="Continue Shopping" /></a>
		<input type="image" src="{$config.dir}images/checkout.gif" width="82" height="22" alt="Checkout &gt;" title="Checkout &gt;" />
	</form>
</div>
