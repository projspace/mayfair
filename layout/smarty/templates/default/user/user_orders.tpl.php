<table border="0" cellpadding="0" cellspacing="0">
	<form method="post" action="{$config.dir}index.php/fuseaction/user.details">
	<tr>
		<td><input type="Submit" class="shopButtonInput" value="Your Details"></td>
	</tr>
	</form>
</table>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr><td colspan="8"><img src="{$config.dir}images/dash.gif" width="100%" height="9"></td></tr>
	<tr><td colspan="8"><br><div class="siteTitle">Pending Orders</div><br></td></tr>
	<tr>
		<td class="shopHeading"><b>ID</b></td>
		<td class="shopHeading"><b>Date</b></td>
		<td class="shopHeading"><b>Time</b></td>
		<td class="shopHeading"><b>Address</b></td>
		<td class="shopHeading"><div class="shopPaddedDiv" align="right"><b>Value</b></div></td>
		<td class="shopHeading"><div class="shopPaddedDiv" align="right"><b>Shipping</b></div></td>
		<td class="shopHeading"><div class="shopPaddedDiv" align="right"><b>Total</b></div></td>
		<td class="shopHeading">&nbsp;</td>
	</tr>
	{foreach from=$pending item=order}
	<tr>
		<td class="shopCart2"><div class="shopPaddedDiv">{$order[$keys.shop_orders_id]}</td>
		<td class="shopCart2"><div class="shopPaddedDiv">{$order[$keys.shop_orders_time]|date_format:"%d/%m/%Y"}</td>
		<td class="shopCart2"><div class="shopPaddedDiv">{$order[$keys.shop_orders_time]|date_format:"%H:%M"}</td>
		<td class="shopCart2"><div class="shopPaddedDiv">{$order[$keys.shop_orders_address]}</td>
		<td class="shopCart2"><div class="shopPaddedDiv" align="right">£{$order[$keys.shop_orders_total]|price}</div></td>
		<td class="shopCart2"><div class="shopPaddedDiv" align="right">£{$order[$keys.shop_orders_shipping]|price}</div></td>
		<td class="shopCart2"><div class="shopPaddedDiv" align="right">£{$order[$keys.shop_orders_shipping]+$order[$keys.shop_orders_total]|price}</div></td>
		<td class="shopCart2"><a class="black" href="{$config.dir}index.php/fuseaction/user.viewOrder/id/{$order.id}">View</a></td>
	</tr>
	<tr><td colspan="8" class="shopSep"><img src="{$config.dir}images/themes/{$config.theme}/trans.gif" width="100%" height="1"></td></tr>
	{/foreach}
	<tr><td colspan="8"><br><div class="siteTitle">Past Orders</div><br></td></tr>
	<tr>
		<td class="shopHeading"><b>ID</b></td>
		<td class="shopHeading"><b>Date</b></td>
		<td class="shopHeading"><b>Time</b></td>
		<td class="shopHeading"><b>Address</b></td>
		<td class="shopHeading"><div class="shopPaddedDiv" align="right"><b>Value</b></div></td>
		<td class="shopHeading"><div class="shopPaddedDiv" align="right"><b>Shipping</b></div></td>
		<td class="shopHeading"><div class="shopPaddedDiv" align="right"><b>Total</b></div></td>
		<td class="shopHeading">&nbsp;</td>
	</tr>
	{foreach from=$orders item=order}
	<tr>
		<td class="shopCart2"><div class="shopPaddedDiv">{$order[$keys.shop_orders_id]}</td>
		<td class="shopCart2"><div class="shopPaddedDiv">{$order[$keys.shop_orders_time]|date_format:"%d/%m/%Y"}</td>
		<td class="shopCart2"><div class="shopPaddedDiv">{$order[$keys.shop_orders_time]|date_format:"%H:%M"}</td>
		<td class="shopCart2"><div class="shopPaddedDiv">{$order[$keys.shop_orders_address]}</td>
		<td class="shopCart2"><div class="shopPaddedDiv" align="right">£{$order[$keys.shop_orders_total]|price}</div></td>
		<td class="shopCart2"><div class="shopPaddedDiv" align="right">£{$order[$keys.shop_orders_shipping]|price}</div></td>
		<td class="shopCart2"><div class="shopPaddedDiv" align="right">£{$order[$keys.shop_orders_shipping]+$order[$keys.shop_orders_total]|price}</div></td>
		<td class="shopCart2"><a class="black" href="{$config.dir}index.php/fuseaction/user.viewOrder/id/{$order.id}">View</a></td>
	</tr>
	<tr><td colspan="8" class="shopSep"><img src="{$config.dir}images/themes/{$config.theme}/trans.gif" width="100%" height="1"></td></tr>
	{/foreach}

</table>