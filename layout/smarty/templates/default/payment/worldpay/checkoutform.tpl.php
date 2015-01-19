<div class="checkoutform">
	<form name="worldpay" method="post" action="https://select.worldpay.com/wcc/purchase">
	<input type="hidden" name="instId" value="{$config.psp.instid}" />
	<input type="hidden" name="cartId" value="{$params.shopsid}" />
	<input type="hidden" name="MC_callback" value="{$config.protocol}{$config.url}{$config.dir}index.php?fuseaction=shop.callback" />
	<input type="hidden" name="MC_total" value="{$params.vars.total}" />
	<input type="hidden" name="MC_shipping" value="{$params.vars.shipping}" />
	<input type="hidden" name="currency" value="{$config.psp.currency}" />
	<input type="hidden" name="amount" value="{$params.vars.total+$params.vars.shipping}" />
	<input type="hidden" name="desc" value="{$config.psp.desc}" />
	<input type="hidden" name="testMode" value="{$config.psp.testmode}" />
	<div class="right"><input class="submit" type="submit" value="Continue" /></div>
	<div class="center"><script type="text/javascript" src="http://www.worldpay.com/cgenerator/cgenerator.php?instId={$config.psp.instid}"></script></div>
	</form>
</div>