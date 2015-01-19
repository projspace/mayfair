<div class="right">
	<form action="https://www.{if $config.psp.test_mode==true}sandbox.{/if}paypal.com/cgi-bin/webscr" method="post">
		<input type="hidden" name="cmd" value="_xclick">
		<input type="hidden" name="business" value="{$config.psp.business}">
		<input type="hidden" name="item_name" value="{$config.psp.item_name}">
		<input type="hidden" name="amount" value="{$params.vars.total|price}">
		<input type="hidden" name="shipping" value="{$params.vars.shipping+$params.vars.packing|price}">
		<input type="hidden" name="return" value="{$config.protocol}{$config.url}{$config.dir}index.php/fuseaction/shop.finished">
		<input type="hidden" name="cancel_return" value="{$config.protocol}{$config.url}{$config.dir}index.php/fuseaction/shop.cancelled">
		<input type="hidden" name="notify_url" value="{$config.protocol}{$config.url}{$config.dir}index.php?fuseaction=shop.callback">
		<input type="hidden" name="no_note" value="1">
		<input type="hidden" name="currency_code" value="{$config.psp.currency}">
		<input type="hidden" name="lc" value="GB">
		<input type="hidden" name="custom" value="{$params.session_id}">

		<input style="float: left;" type="checkbox" id="terms" name="terms"{if $params.request.terms=="on"} checked="checked"{/if} onclick="if(this.checked) document.getElementById('paypal_submit').style.visibility='visible'; else document.getElementById('paypal_submit').style.visibility='hidden';" />
		<label style="width: auto;">I accept the <a href="{$config.dir}termsandconditions" target="_new">Terms and Conditions</a> of sale</label><br />

		<input id="paypal_submit" class="noBorder" type="image" src="https://www.paypal.com/en_US/i/btn/x-click-but6.gif" border="0" name="submit" alt="Make payments with PayPal - it's fast, free and secure!">
		<script type="text/javascript"> document.getElementById('paypal_submit').style.visibility='hidden'; </script>
	</form>
</div>