<form method="post" action="{$config.psp.url}" id="frmPayment">

	<input type='hidden' name="x_type" value="{$config.psp.type}" />
	<input type='hidden' name="x_login" value="{$config.psp.api_login_id}" />
	<input type='hidden' name="x_fp_hash" value="{$params.vars.fingerprint}" />
	<input type='hidden' name="x_amount" value="{$params.vars.transactionamount|number_format:2:".":""}" />
	<input type='hidden' name="x_fp_timestamp" value="{$params.vars.fp_timestamp}" />
	<input type='hidden' name="x_fp_sequence" value="{$params.session_id}" />
	<input type='hidden' name="x_po_num" value="{$params.session_id}" />
	<input type='hidden' name="x_version" value="{$config.psp.version}">
	<input type='hidden' name="x_show_form" value="payment_form">
	<input type='hidden' name="x_test_request" value="{$config.psp.test_request}" />
	<input type='hidden' name="x_method" value="{$config.psp.method}" />
	
	
	<input type="hidden" name="x_first_name" value="{$params.billing.first_name}">
	<input type="hidden" name="x_last_name" value="{$params.billing.last_name}">
	<input type="hidden" name="x_email" value="{$params.billing.email}">
	<input type="hidden" name="x_phone" value="{$params.billing.phone}">
	<input type="hidden" name="x_address" value="{$params.billing.line1} {$params.billing.line2} {$params.billing.line3}">
	<input type="hidden" name="x_city" value="{$params.billing.line4}">
	<input type="hidden" name="x_state" value="{$params.billing.country}">
	<input type="hidden" name="x_zip" value="{$params.billing.postcode}">
	<input type="hidden" name="x_country" value="USA">
	
	<input type="hidden" name="x_ship_to_first_name" value="{$params.delivery.first_name}">
	<input type="hidden" name="x_ship_to_last_name" value="{$params.delivery.last_name}">
	<input type="hidden" name="x_ship_to_address" value="{$params.delivery.line1} {$params.delivery.line2} {$params.delivery.line3}">
	<input type="hidden" name="x_ship_to_city" value="{$params.delivery.line4}">
	<input type="hidden" name="x_ship_to_state" value="{$params.delivery.country}">
	<input type="hidden" name="x_ship_to_zip" value="{$params.delivery.postcode}">
	<input type="hidden" name="x_ship_to_country" value="USA">
	
	
	<!-- Callback Settings --> 
	<!-- RELAY RESPONSE -->
	<input type="hidden" name="x_delim_data" value="FALSE" />
	<input type="hidden" name="x_relay_response" value="TRUE" /> <!-- This field is paired with x_delim_data. If one is set to True, the other must be set to False. -->
	<input type="hidden" name="x_relay_url" value="{$config.dir|replace:'https://':'http://'}callback">
	<!-- <input type="hidden" name="x_relay_url" value="http://www.blochworld.com/callback"> -->
	
	<!-- RECEIPT RESPONSE -->
	<input type="hidden" name="x_receipt_link_method" value="POST" />
	<input type="hidden" name="x_receipt_link_text" value="Click here to finnish the order" />
	<input type="hidden" name="x_receipt_link_url" value="{$config.dir}order-confirmation/{$params.session_id}">
	<!-- <input type="hidden" name="x_receipt_link_url" value="https://www.blochworld.com/order-confirmation/{$params.session_id}"> -->
	
	<input type="hidden" name="x_logo_url" value="{$config.dir}layout/templates/bloch/images/logo.gif">
	
	<!--  Cancel Settings -->
	<input type="hidden" name="x_cancel_url_text" value="Cancel current order">
	<input type="hidden" name="x_cancel_url" value="{$config.dir}cancel">
	
</form>

<div class="banner banner-small">
    <div class="banner-info">
        <div class="banner-content billing-img">
            <h2 class="golden">Payment Redirect</h2>
            <p>You are being redirected to the Bloch's Secure Payment System.</p>
            <p>Please do not refresh the page as this may result in payment being put through twice.</p>
            <p>If not redirected in 5 seconds, please <a href="{$config.dir}contact-us">click here</a> to be taken to the Contact Us page where you can contact us regarding your order.</p>
        </div>
    </div>
</div>