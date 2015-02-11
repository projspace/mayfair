<table border="0" cellpadding="0" cellspacing="0">
	<form method="post" action="{$config.dir}index.php/fuseaction/user.reorder/id/{$order.id}">
	<tr>
		<td><input type="Submit" class="shopButtonInput" value="Re-order"></td>
	</tr>
	</form>
</table>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr><td><img src="{$config.dir}images/dash.gif" width="100%" height="9"></td></tr>
	<tr><td><br><div class="siteTitle">Order Details</div><br></td>
	<tr><td>
		<table border="0" cellpadding="2" cellspacing="2">
			<tr>
				<td class="shopField">Time</td>
				<td>{$order.time|date_format:"%H:%M %d/%m/%Y"}</td>
			</tr>
			{if $order.processed neq 0}
			<tr>
				<td class="shopField">Processed</td>
				<td>{$order.processed|date_format:"%H:%M %d/%m/%Y"}</td>
			</tr>
			{/if}
			<tr>
				<td class="shopField">Customer Name</td>
				<td>{$order.name}</td>
			</tr>
			<tr>
				<td class="shopField">Address</td>
				<td>{$order.address|replace:"\n":"<br>"}</td>
			</tr>
			<tr>
				<td class="shopField">Postcode</td>
				<td>{$order.postcode}</td>
			</tr>
			<tr>
				<td class="shopField">Country</td>
				<td>{$country.name}</td>
			</tr>
		</table>
	</td></tr>
	<tr><td><img src="{$config.dir}images/dash.gif" width="100%" height="9"></td></tr>
	<tr><td><br><div class="siteTitle">Products</div><br></td></tr>
</table>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td class="shopHeading"></td>
		<td class="shopHeading"><b>Product</b></td>
		<td class="shopHeading"><b>Options</b></td>
		<td class="shopHeading"><div class="shopPaddedDiv" align="right"><b>Price</b></div></td>
		<td class="shopHeading"><div class="shopPaddedDiv" align="right"><b>Qty.</b></div></td>
		<td class="shopHeading"><div class="shopPaddedDiv" align="right"><b>Total</b></div></td>
	</tr>
	{counter start=0 skip=1 print=false}
	{foreach from=$products item=product}
	<tr>
		<td class="shopCart2"><div class="shopPaddedDiv">{counter}</div></td>
		<td class="shopCart2"><div class="shopPaddedDiv">{$product[$keys.shop_brands_name]} :: {$product[$keys.shop_products_name]}</div></td>
		<td class="shopCart2"><div class="shopPaddedDiv">
		{foreach from=$product[$keys.shop_products_options]|unserialize item=option}
		{if $option.name neq ""}
			{$option.name}<br>
		{/if}
		{/foreach}
		</div></td>
		<td class="shopCart2"><div class="shopPaddedDiv" align="right">£{$product[$keys.shop_products_price]+$product[$keys.shop_order_products_optionprice]|price}</div></td>
		<td class="shopCart2"><div class="shopPaddedDiv" align="right">{$product[$keys.shop_order_products_quantity]}</div></td>
		<td class="shopCart2"><div class="shopPaddedDiv" align="right">£{$product[$keys.shop_products_price]+$product[$keys.shop_order_products_optionprice]*$product[$keys.shop_order_products_quantity]|price}</div></td>
	</tr>
	<tr><td class="shopSep" colspan="6"><img src="{$config.dir}images/themes/{$config.theme}/trans.gif" width="100%" height="1"></td></tr>
	{/foreach}
	<tr>
		<td class="shopHeading" colspan="5"><div class="shopPaddedDiv" align="right">Subtotal</div></td>
		<td class="shopHeading"><div class="shopPaddedDiv" align="right">£{$order.total|price}</div></td>
	</tr>
	<tr>
		<td class="shopHeading" colspan="5"><div class="shopPaddedDiv" align="right">Shipping</div></td>
		<td class="shopHeading"><div class="shopPaddedDiv" align="right">£{$order.shipping|price}</div></td>
	</tr>
	<tr>
		<td class="shopHeading" colspan="5"><div class="shopPaddedDiv" align="right">Total</div></td>
		<td class="shopHeading"><div class="shopPaddedDiv" align="right">£{$order.total+$order.shipping|price}</div></td>
	</tr>
	<tr>
		<td><img src="{$config.dir}images/themes/{$config.theme}/trans.gif" width="1" height="4"></td>
	</tr>
</table>
<table border="0" cellpadding="0" cellspacing="0">
	<form method="post" action="{$config.dir}index.php/fuseaction/user.reorder/id/{$order.id}">
	<tr>
		<td><input type="Submit" class="shopButtonInput" value="Re-order"></td>
	</tr>
	</form>
</table>