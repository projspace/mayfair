<!-- 
INSTRUCTIONS:
Put this hidden <form> anywhere on your page with the token from the GetHostedProfilePage API call.
-->


<form method="post" action="<?php print $config['psp']['profile_url'] ?><?php print $authorizeAction ?>" id="formAuthorizeNetPopup" name="formAuthorizeNetPopup" style="display:none;">
  <input type="hidden" name="Token" value="<?php print $token;?>" />
  <input type="hidden" name="PaymentProfileId" value="<?php print $payment_profile_id?>" />
  <input type="hidden" name="ShippingAddressId" value="<?php print $shipping_profile_id?>" />
</form>
<? $elems->placeholder('script')->captureStart() ?>
<script type="text/javascript">
    $(document).ready(function(){
        $('form#formAuthorizeNetPopup').submit();
    });
</script>
<? $elems->placeholder('script')->captureEnd() ?>