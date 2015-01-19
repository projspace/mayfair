<script language="javascript" type="text/javascript">
/* <![CDATA[ */
{literal}
	$(document).ready(function(){
		$('#frmPayment').submit();
	});
{/literal}
/* ]]> */
</script>
<form method="post" action="{$config.psp.url}secuitems.php" id="frmPayment">
	<input type="hidden" name="filename" value="{$config.psp.shreference}/template.html">
	
	<input type="hidden" name="shreference" value="{$config.psp.shreference}">
	<input type="hidden" name="checkcode" value="{$config.psp.checkcode}">
	<input type="hidden" name="transactioncurrency" value="{$config.psp.currency}">
	
	<input type="hidden" name="cardholdersname" value="{$params.billing.name}">
	<input type="hidden" name="cardholdersemail" value="{$params.billing.email}">
	<input type="hidden" name="cardholdertelephonenumber" value="{$params.billing.phone}">
	<input type="hidden" name="cardholderaddr1" value="{$params.billing.line1} {$params.billing.line2}">
	<input type="hidden" name="cardholderaddr2" value="">
	<input type="hidden" name="cardholdercity" value="{$params.billing.line3}">
	<input type="hidden" name="cardholderstate" value="{$params.billing.line4}">
	<input type="hidden" name="cardholderpostcode" value="{$params.billing.postcode}">
	<input type="hidden" name="cardholdercountry" value="{$params.billing.country}">
	
	<input type="hidden" name="shippingcharge" value="{$params.vars.shippingcharge|number_format:2:".":""}">
	<input type="hidden" name="transactiontax" value="{$params.vars.transactiontax|number_format:2:".":""}">
	
	<input type="hidden" name="subtotal" value="{$params.vars.subtotal|number_format:2:".":""}">
	<input type="hidden" name="vat" value="{$params.vars.vat|number_format:2:".":""}">
	<input type="hidden" name="total" value="{$params.vars.total|number_format:2:".":""}">
	<input type="hidden" name="transactionamount" value="{$params.vars.transactionamount|number_format:2:".":""}">
	
	<input type="hidden" name="callbackurl" value="{$config.protocol}{$config.url}{$config.dir}callback" /><!-- NO '?' allowed in the callback url -->
	<input type="hidden" name="callbackdata" value="custom|{$params.session_id}|name|#cardholdersname|email|#cardholdersemail|amount|#transactionamount|street|#cardholderaddr1|city|#cardholdercity|state|#cardholderstate|country|#cardholdercountry|postcode|#cardholderpostcode|tel|#cardholdertelephonenumber" />
	
	<input type="hidden" name="success_url" value="{$config.protocol}{$config.url}{$config.dir}order-confirmation/{$params.session_id}" />
	<input type="hidden" name="failed_url" value="{$config.protocol}{$config.url}{$config.dir}order-failed" />
	
	<input type="hidden" name="secuitems" value="[||{$config.psp.item_name}|{$params.vars.transactionamount|number_format:2:".":""}|1|{$params.vars.transactionamount|number_format:2:".":""}]">
	
	<input type="hidden" name="domain" value="{if $config.psp.test_mode}test{else}www{/if}">
	<input type="hidden" name="site" value="{$config.protocol}{$config.url}{$config.dir}">
	<input type="hidden" name="mobile" value="{$params.mobile}">
</form>
<p>You are being redirected to the Bloch's Secure Payment System.</p>
<p>Please do not refresh the page as this may result in payment being put through twice.</p>
<p>If not redirected in 5 seconds, please <a href="{$config.dir}contact-us">click here</a> to be taken to the Contact Us page where you can contact us regarding your order.</p>