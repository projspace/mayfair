<?php /* Smarty version 2.6.18, created on 2013-05-01 09:46:29
         compiled from default/payment/authorize/details.tpl.php */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'number_format', 'default/payment/authorize/details.tpl.php', 6, false),array('modifier', 'replace', 'default/payment/authorize/details.tpl.php', 39, false),)), $this); ?>
<form method="post" action="<?php echo $this->_tpl_vars['config']['psp']['url']; ?>
" id="frmPayment">

	<input type='hidden' name="x_type" value="<?php echo $this->_tpl_vars['config']['psp']['type']; ?>
" />
	<input type='hidden' name="x_login" value="<?php echo $this->_tpl_vars['config']['psp']['api_login_id']; ?>
" />
	<input type='hidden' name="x_fp_hash" value="<?php echo $this->_tpl_vars['params']['vars']['fingerprint']; ?>
" />
	<input type='hidden' name="x_amount" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['params']['vars']['transactionamount'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ".", "") : smarty_modifier_number_format($_tmp, 2, ".", "")); ?>
" />
	<input type='hidden' name="x_fp_timestamp" value="<?php echo $this->_tpl_vars['params']['vars']['fp_timestamp']; ?>
" />
	<input type='hidden' name="x_fp_sequence" value="<?php echo $this->_tpl_vars['params']['session_id']; ?>
" />
	<input type='hidden' name="x_po_num" value="<?php echo $this->_tpl_vars['params']['session_id']; ?>
" />
	<input type='hidden' name="x_version" value="<?php echo $this->_tpl_vars['config']['psp']['version']; ?>
">
	<input type='hidden' name="x_show_form" value="payment_form">
	<input type='hidden' name="x_test_request" value="<?php echo $this->_tpl_vars['config']['psp']['test_request']; ?>
" />
	<input type='hidden' name="x_method" value="<?php echo $this->_tpl_vars['config']['psp']['method']; ?>
" />
	
	
	<input type="hidden" name="x_first_name" value="<?php echo $this->_tpl_vars['params']['billing']['first_name']; ?>
">
	<input type="hidden" name="x_last_name" value="<?php echo $this->_tpl_vars['params']['billing']['last_name']; ?>
">
	<input type="hidden" name="x_email" value="<?php echo $this->_tpl_vars['params']['billing']['email']; ?>
">
	<input type="hidden" name="x_phone" value="<?php echo $this->_tpl_vars['params']['billing']['phone']; ?>
">
	<input type="hidden" name="x_address" value="<?php echo $this->_tpl_vars['params']['billing']['line1']; ?>
 <?php echo $this->_tpl_vars['params']['billing']['line2']; ?>
 <?php echo $this->_tpl_vars['params']['billing']['line3']; ?>
">
	<input type="hidden" name="x_city" value="<?php echo $this->_tpl_vars['params']['billing']['line4']; ?>
">
	<input type="hidden" name="x_state" value="<?php echo $this->_tpl_vars['params']['billing']['country']; ?>
">
	<input type="hidden" name="x_zip" value="<?php echo $this->_tpl_vars['params']['billing']['postcode']; ?>
">
	<input type="hidden" name="x_country" value="USA">
	
	<input type="hidden" name="x_ship_to_first_name" value="<?php echo $this->_tpl_vars['params']['delivery']['first_name']; ?>
">
	<input type="hidden" name="x_ship_to_last_name" value="<?php echo $this->_tpl_vars['params']['delivery']['last_name']; ?>
">
	<input type="hidden" name="x_ship_to_address" value="<?php echo $this->_tpl_vars['params']['delivery']['line1']; ?>
 <?php echo $this->_tpl_vars['params']['delivery']['line2']; ?>
 <?php echo $this->_tpl_vars['params']['delivery']['line3']; ?>
">
	<input type="hidden" name="x_ship_to_city" value="<?php echo $this->_tpl_vars['params']['delivery']['line4']; ?>
">
	<input type="hidden" name="x_ship_to_state" value="<?php echo $this->_tpl_vars['params']['delivery']['country']; ?>
">
	<input type="hidden" name="x_ship_to_zip" value="<?php echo $this->_tpl_vars['params']['delivery']['postcode']; ?>
">
	<input type="hidden" name="x_ship_to_country" value="USA">
	
	
	<!-- Callback Settings --> 
	<!-- RELAY RESPONSE -->
	<input type="hidden" name="x_delim_data" value="FALSE" />
	<input type="hidden" name="x_relay_response" value="TRUE" /> <!-- This field is paired with x_delim_data. If one is set to True, the other must be set to False. -->
	<input type="hidden" name="x_relay_url" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['config']['dir'])) ? $this->_run_mod_handler('replace', true, $_tmp, 'https://', 'http://') : smarty_modifier_replace($_tmp, 'https://', 'http://')); ?>
callback">
	<!-- <input type="hidden" name="x_relay_url" value="http://www.blochworld.com/callback"> -->
	
	<!-- RECEIPT RESPONSE -->
	<input type="hidden" name="x_receipt_link_method" value="POST" />
	<input type="hidden" name="x_receipt_link_text" value="Click here to finnish the order" />
	<input type="hidden" name="x_receipt_link_url" value="<?php echo $this->_tpl_vars['config']['dir']; ?>
order-confirmation/<?php echo $this->_tpl_vars['params']['session_id']; ?>
">
	<!-- <input type="hidden" name="x_receipt_link_url" value="https://www.blochworld.com/order-confirmation/<?php echo $this->_tpl_vars['params']['session_id']; ?>
"> -->
	
	<input type="hidden" name="x_logo_url" value="<?php echo $this->_tpl_vars['config']['dir']; ?>
layout/templates/mayfair/images/img-logo.png">
	
	<!--  Cancel Settings -->
	<input type="hidden" name="x_cancel_url_text" value="Cancel current order">
	<input type="hidden" name="x_cancel_url" value="<?php echo $this->_tpl_vars['config']['dir']; ?>
cancel">
	
</form>

<div class="banner banner-small">
    <div class="banner-info">
        <div class="banner-content billing-img">
            <h2 class="golden">Payment Redirect</h2>
            <p>You are being redirected to the Mayfairhouse Secure Payment System.</p>
            <p>Please do not refresh the page as this may result in payment being put through twice.</p>
            <p>If not redirected in 5 seconds, please <a href="<?php echo $this->_tpl_vars['config']['dir']; ?>
contact-us">click here</a> to be taken to the Contact Us page where you can contact us regarding your order.</p>
        </div>
    </div>
</div>