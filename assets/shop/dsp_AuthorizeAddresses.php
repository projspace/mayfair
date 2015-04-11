<?
	$page = $elems->qry_Page(30);
	$countries = $countries->GetRows();

	foreach($countries as $row)
	if($session->session->fields['delivery_country_id']+0 == $row['id']) {
		$default_state = $row['name'];
		break;
	}
?>
<?
    $delivery_addresses = array();
    foreach($authorize_profile_xml->xml->profile->shipToList as $address)
    {
        $found = false;
        foreach($countries as $row)
            if(strcasecmp(trim($address->state), trim($row['name'])) == 0 || strcasecmp(trim($address->state), trim($row['code'])) == 0)
            {
                $found = true;
                break;
            }
        if($found)
        {
            $addr = array();
            $addr['name'] = trim($address->firstName." ".$address->lastName);
            $addr['phone'] = trim($address->phoneNumber);
            $addr['line1'] = trim(str_replace(array("\r\n","\n", "\r"), " ",$address->address));
            $addr['line4'] = trim($address->city);
            $addr['postcode'] = trim($address->zip);
            $addr['country'] = trim($address->state);
            $addr['ctry'] = trim($address->country);
            $addr['country_id'] = $row['id'];

            $delivery_addresses[$address->customerAddressId.''] = $addr;
        }
    }

    $billing_addresses = array();
    foreach($authorize_profile_xml->xml->profile->paymentProfiles as $address)
    {
        $found = false;
        foreach($countries as $row)
            if(strcasecmp(trim($address->billTo->state), trim($row['name'])) == 0 || strcasecmp(trim($address->billTo->state), trim($row['code'])) == 0)
            {
                $found = true;
                break;
            }

        $addr = array();
        $addr['name'] = trim($address->billTo->firstName." ".$address->billTo->lastName);
        $addr['phone'] = trim($address->billTo->phoneNumber);
        $addr['line1'] = trim(str_replace(array("\r\n","\n", "\r"), " ",$address->billTo->address));
        $addr['line4'] = trim($address->billTo->city);
        $addr['postcode'] = trim($address->billTo->zip);
        if($found)
            $addr['country'] = trim($address->billTo->state);
        else
            $addr['country'] = trim($address->billTo->country);
        //$addr['country_id'] = $row['id'];
        $addr['card'] = trim($address->payment->creditCard->cardNumber);

        $billing_addresses[$address->customerPaymentProfileId.''] = $addr;
    }
?>
<style type="text/css">
    .add_billing, .add_delivery { width: 190px !important; margin-top: 22px !important; }
    #checkOut .contactForm h3 { padding-top: 0 !important; }
    label.subtext { position: relative; }
    label.subtext em { font-size: 50% !important; left: 0; position: absolute; top: 20px; width: 200px; }
    span.customSelect { overflow: hidden; }
</style>
<div id="checkOut">
    <div class="block">
        <div class="tab-wrapper">
            <h1><?=$page['name'] ?></h1>
            <ul class="tab-nav">
                <li class="active"><a href="#" >addresses</a></li>
                <li class="inactive"><a href="#" >taxes</a></li>
                <li class="inactive"><a href="#" >payment</a></li>
                <li class="inactive"><a href="#" >thank you</a></li>
            </ul>
            <div id="tab-content">
                <form method="post" action="<?=$config['dir'] ?>checkout?act=saveLogedInDetails" id='addrForm'>
                    <input type='hidden' name='customerProfileId' value='<?php print $account['authorize_profile_id']?>' />

                    <div class="contactForm" style="padding-top: 0;">
                        <?//=$page['content'] ?>
                        <?=$validator->displayMessage() ?>
                        <div class="detail-section">
                            <h3 class="capital">Billing address</h3>

                            <? if(count($billing_addresses)): ?>
                            <div class="block">
                                <label for="paymentProfileId">Choose address</label>
                                <div class="report-box custom-select text-big">
                                    <select class="styled select_address" id="paymentProfileId" name="paymentProfileId" rel="billing_addresses">
                                        <option value="">Select billing</option>
                                    <?
                                        foreach($billing_addresses as $paymentProfileId=>$row)
                                        {
                                            $display = array();
                                            if($row['card'])
                                                $display[] = $row['card'];
                                            if($row['name'])
                                                $display[] = $row['name'];
                                            if($row['line1'])
                                                $display[] = $row['line1'];
                                            if($row['line4'])
                                                $display[] = $row['line4'];
                                            if($row['country'])
                                                $display[] = $row['country'];
                                            if($row['postcode'])
                                                $display[] = $row['postcode'];
                                            if($row['phone'])
                                                $display[] = $row['phone'];

                                            if($session->session->fields['paymentProfileId'] == $paymentProfileId)
                                                echo '<option value="'.$paymentProfileId.'" selected="selected">'.implode(',',$display).'</value>';
                                            else
                                                echo '<option value="'.$paymentProfileId.'">'.implode(',',$display).'</value>';
                                        }
                                    ?>
                                    </select>
                                    <a href="#" class="btn-gray edit_payment_address" style="padding: 0 10px; margin-left: 5px; display: none;">Edit</a>
                                </div>
                                <div class="clear"></div>
                            </div>
                            <? endif; ?>
                            <div class="block">
                                <label>&nbsp;</label>
                                <a href="<?php print $config['dir']?>index.php?fuseaction=user.addAuthorizePaymentProfile&ajax=1>" class="btn big-btn green-btn add_billing">Add Payment Details</a>
                                <p style="padding-left: 206px;">Click on this button to add a new billing address</p>
                                <div class="clear"></div>
                            </div>
                            <div class="block">
                                <label for="cvv">Card Code</label>
                                <input type="text" class="input-box clearable" value="" placeholder="required" name="cvv" id="cvv" />
                                <div class="clear"></div>
                                <hr style="position: absolute; bottom: -23px; width: 845px" />
                            </div>
                            <div class="block">
                                <label for="billing_name">Name</label>
                                <input type="text" class="input-box clearable input_name" value="" placeholder="required" name="billing_name" id="billing_name" readonly="readonly" />
                                <div class="clear"></div>
                            </div>
                            <div class="block">
                                <label for="billing_email">Email</label>
                                <input type="text" class="input-box clearable input_email" value="<?= $account['email']?>" placeholder="required" name="billing_email" id="billing_email" readonly="readonly" />
                                <div class="clear"></div>
                            </div>
                            <div class="block">
                                <label for="billing_phone">Phone</label>
                                <input type="text" class="input-box clearable input_phone" value="" placeholder="required" name="billing_phone" id="billing_phone" readonly="readonly" />
                                <div class="clear"></div>
                            </div>
                            <div class="block">
                                <label for="billing_line1">Address 1</label>
                                <input type="text" class="input-box clearable input_line1" value="" placeholder="required" name="billing_line1" id="billing_line1" readonly="readonly" />
                                <div class="clear"></div>
                            </div>
                            <div class="block">
                                <label for="billing_line2">Address 2</label>
                                <input type="text" class="input-box clearable input_line2" value="" name="billing_line2" id="billing_line2" readonly="readonly" />
                                <div class="clear"></div>
                            </div>
                            <div class="block">
                                <label for="billing_line3">Address 3</label>
                                <input type="text" class="input-box clearable input_line3" value="" name="billing_line3" id="billing_line3" readonly="readonly" />
                                <div class="clear"></div>
                            </div>
                            <div class="block">
                                <label for="billing_line4">City</label>
                                <input type="text" class="input-box clearable input_line4" value="" placeholder="required" name="billing_line4" id="billing_line4" readonly="readonly" />
                                <div class="clear"></div>
                            </div>
                            <div class="block">
                                <label for="billing_postcode">Zip code</label>
                                <input type="text" class="input-box clearable input_postcode" value="" placeholder="required" name="billing_postcode" id="billing_postcode" readonly="readonly" />
                                <div class="clear"></div>
                            </div>
                            <div class="block">
                                <label for="billing_country">State</label>
                                <input type="text" class="input-box clearable input_country" value="" placeholder="required" name="billing_country" id="billing_country" readonly="readonly" />
                                <input type="hidden" class="input_country_id" id="billing_country_id" name="billing_country_id" value="" />
                            </div>
                            <? if($session->session->fields['last_gift_list_id']+0): ?>
                            <div class="block"><p style="margin-top: 10px;">mayfairhouse.com is happy to provide complimentary ground shipping to the address specified by the gift registrant.</p></div>
                            <div class="block">
                                <a href="#" class="btn big-btn green-btn top-space right-space submit">PAYMENT</a>
                                <div class="clear"></div>
                            </div>
                            <? endif; ?>
                        </div>
                        <? if(!$session->session->fields['last_gift_list_id']+0): ?>
                        <div class="detail-section">
                            <h3 class="capital">DELIVERY address</h3>

                            <? if(count($delivery_addresses)): ?>
                            <div class="block">
                                <label for="customerAddressId" class="subtext">
                                    Choose address
                                    <em>Please make sure that the address you are using is in the same state as the option that you have chosen in the cart stage.</em>
                                </label>
                                <div class="report-box custom-select text-big">
                                    <select class="styled select_address" id="customerAddressId" name="customerAddressId" rel="delivery_addresses">
                                        <option value="">Select delivery</option>
                                    <?
                                        foreach($delivery_addresses as $customerAddressId=>$row)
                                        {
                                            $display = array();
                                            if($row['name'])
                                                $display[] = $row['name'];
                                            if($row['line1'])
                                                $display[] = $row['line1'];
                                            if($row['line4'])
                                                $display[] = $row['line4'];
                                            if($row['country'])
                                                $display[] = $row['country'];
                                            if($row['postcode'])
                                                $display[] = $row['postcode'];
                                            if($row['phone'])
                                                $display[] = $row['phone'];

                                            if($session->session->fields['customerAddressId'] == $customerAddressId)
                                                echo '<option value="'.$customerAddressId.'" selected="selected">'.implode(',',$display).'</value>';
                                            else
                                                echo '<option value="'.$customerAddressId.'">'.implode(',',$display).'</value>';
                                        }
                                    ?>
                                    </select>
                                </div>
                                <a href="#" class="btn-gray edit_shipping_address" style="padding: 0 10px; margin-left: 5px; display: none; position: relative; z-index: 99;">Edit</a>
                            </div>
                            <? endif; ?>
                            <div class="block">
                                <label>&nbsp;</label>
                                <a href="<?php print $config['dir']?>index.php?fuseaction=user.addAuthorizeShipingProfile&ajax=1>" class="btn big-btn green-btn add_delivery">Add Delivery Address</a>
                                <p style="padding-left: 206px;">Click on this button to add a new delivery address</p>
                                <div class="clear"></div>
                            </div>
                            <div class="block">
                                <label style="margin-top: 34px;">&nbsp;</label>
                                <div class="clear"></div>
                            </div>
                            <div class="block">
                                <label for="delivery_name">Name</label>
                                <input type="text" class="input-box clearable input_name" value="" placeholder="required" name="delivery_name" id="delivery_name" readonly="readonly" />
                                <div class="clear"></div>
                            </div>
                            <div class="block">
                                <label for="delivery_email">Email</label>
                                <input type="text" class="input-box clearable input_email" value="<?= $account['email']?>" placeholder="required" name="delivery_email" id="delivery_email" readonly="readonly" />
                                <div class="clear"></div>
                            </div>
                            <div class="block">
                                <label for="delivery_phone">Phone</label>
                                <input type="text" class="input-box clearable input_phone" value="" placeholder="required" name="delivery_phone" id="delivery_phone" readonly="readonly" />
                                <div class="clear"></div>
                            </div>
                            <div class="block">
                                <label for="delivery_line1">Address 1</label>
                                <input type="text" class="input-box clearable input_line1" value="" placeholder="required" name="delivery_line1" id="delivery_line1" readonly="readonly" />
                                <div class="clear"></div>
                            </div>
                            <div class="block">
                                <label for="delivery_line2">Address 2</label>
                                <input type="text" class="input-box clearable input_line2" value="" name="delivery_line2" id="delivery_line2" readonly="readonly" />
                                <div class="clear"></div>
                            </div>
                            <div class="block">
                                <label for="delivery_line3">Address 3</label>
                                <input type="text" class="input-box clearable input_line3" value="" name="delivery_line3" id="delivery_line3" readonly="readonly" />
                                <div class="clear"></div>
                            </div>
                            <div class="block">
                                <label for="delivery_line4">City</label>
                                <input type="text" class="input-box clearable input_line4" value="" placeholder="required" name="delivery_line4" id="delivery_line4" readonly="readonly" />
                                <div class="clear"></div>
                            </div>
                            <div class="block">
                                <label for="delivery_postcode">Zip code</label>
                                <input type="text" class="input-box clearable input_postcode" value="" placeholder="required" name="delivery_postcode" id="delivery_postcode" readonly="readonly" />
                                <div class="clear"></div>
                            </div>
                            <div class="block">
                                <label for="delivery_country">State</label>
                                <input type="text" class="input-box clearable input_country" value="" placeholder="required" name="delivery_country" id="delivery_country" readonly="readonly" />
                                <input type="hidden" class="input_country_id" id="delivery_country_id" name="delivery_country_id" value="" />
                            </div>
                            <div class="block"> <a href="#" class="btn big-btn green-btn top-space right-space submit">PAYMENT</a> <div class="clear"></div> </div>
                        </div>
                        <? endif; ?>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<? $elems->placeholder('script')->captureStart() ?>
<script type="text/javascript" src="<?=$config['dir'] ?>VLib/js/validator.js"></script>
<script language="javascript" type="text/javascript">
/* <![CDATA[ */
	var fields = [];
	fields[fields.length] = {'id':'billing_name', 'name':"Name", 'type':'required|name'};
	fields[fields.length] = {'id':'billing_email', 'name':"Email", 'type':'required|email'};
	fields[fields.length] = {'id':'billing_phone', 'name':"Phone", 'type':'required|phone'};
	fields[fields.length] = {'id':'billing_line1', 'name':"Address Line 1", 'type':'required'};
	fields[fields.length] = {'id':'billing_postcode', 'name':"Postcode", 'type':'required|postcode'};
	fields[fields.length] = {'id':'billing_country', 'name':"Country", 'type':'required'};
	fields[fields.length] = {'id':'cvv', 'name':"Card Code", 'type':'required'};

	<? if(!$session->session->fields['last_gift_list_id']+0): ?>
	fields[fields.length] = {'id':'delivery_name', 'name':"Name", 'type':'required|name'};
	fields[fields.length] = {'id':'delivery_email', 'name':"Email", 'type':'required|email'};
	fields[fields.length] = {'id':'delivery_phone', 'name':"Phone", 'type':'required|phone'};
	fields[fields.length] = {'id':'delivery_line1', 'name':"Address Line 1", 'type':'required'};
	fields[fields.length] = {'id':'delivery_line4', 'name':"Address Line 4", 'type':'required'};
	fields[fields.length] = {'id':'delivery_postcode', 'name':"Postcode", 'type':'required|postcode'};
	fields[fields.length] = {'id':'delivery_country', 'name':"Country", 'type':'required'};
	<? endif; ?>

	function validateFRM()
	{
		$('#addrForm input, #addrForm textarea').removeClass('error').removeClass('valid');
		$('#addrForm label.error, #addrForm label.valid').remove();

        for(i=0;i<fields.length;i++)
            if($('#'+fields[i].id).val() == $('#'+fields[i].id).attr('placeholder'))
                $('#'+fields[i].id).val('');
        
		var validation = new Validator(function(errors){
			for(i=0;i<errors.length;i++)
			{
				$(errors[i].dom).addClass('error').after('<label class="error" for="'+$(this).attr('id')+'" generated="true">'+errors[i].errorMsg+'</label>');
			}
		});

		for(i=0;i<fields.length;i++)
			validation.addField(fields[i].id, fields[i].name, fields[i].type);

		var ret = validation.validate();

		for(i=0;i<fields.length;i++)
        {
            if(!$('#'+fields[i].id+' ~ label.error').length)
                $('#'+fields[i].id).addClass('valid').after('<label class="error valid" for="'+$(this).attr('id')+'" generated="true"></label>');

            if($('#'+fields[i].id).val() == '')
                $('#'+fields[i].id).val($('#'+fields[i].id).attr('placeholder'));
        }
		return ret;
	}

	function validateInput()
	{
		var validation = new Validator(function(errors){});
		for(i=0;i<fields.length;i++)
			validation.addField(fields[i].id, fields[i].name, fields[i].type);

		var ret = validation.validateInput($(this).attr('id'));
		$(this).removeClass('error').removeClass('valid').find(' ~ label.error').remove();
		if(!ret.status)
			$(this).addClass('error').after('<label class="error" for="'+$(this).attr('id')+'" generated="true">'+ret.details.errorMsg+'</label>');
		else
			$(this).addClass('valid').after('<label class="error valid" for="'+$(this).attr('id')+'" generated="true"></label>');
	}

	$(document).ready(function(){
		$('#addrForm').submit(validateFRM);
        $('#addrForm .submit').click(function(){
            $('#addrForm').submit();
            return false;
        });

		$('#billing_name, #billing_email, #billing_phone, #billing_line1, #billing_postcode, #billing_country_id, #delivery_name, #delivery_email, #delivery_phone, #delivery_line1, #delivery_line4, #delivery_postcode, #delivery_country_id').keyup(validateInput).change(validateInput);

        // Authorize requests
		$(".add_billing").fancybox({
		    'width'				: 435
			,'height'			: 513
			,'type'				: 'iframe'
			,'modal'			: false
            ,'padding'			: 0
            ,'overlayColor'		: '#CCCCCC'
            , onStart: function(){
                $('#fancybox-close').css({top: '10px', right: '10px', width: '15px', height: '15px', background: '#CCCCCC url("'+config_dir+'layout/templates/mayfair/css/fancybox/close.gif") no-repeat scroll'});
            }
			,onClosed: function(){
				window.location.reload(true);
			}
		});

		$(".add_delivery").fancybox({
		    'width'				: 385
			,'height'			: 360
			,'type'				: 'iframe'
			,'modal'			: false
            ,'padding'			: 0
            ,'overlayColor'		: '#CCCCCC'
            , onStart: function(){
                $('#fancybox-close').css({top: '10px', right: '10px', width: '15px', height: '15px', background: '#CCCCCC url("'+config_dir+'layout/templates/mayfair/css/fancybox/close.gif") no-repeat scroll'});
            }
			,onClosed: function(){
				window.location.reload(true);
			}
		});

		$('#customerAddressId').change(function(e){
			$(this).parent().find('label.error').remove();
			var val = $.trim($(this).val());
			if(val == '')
			{
				$(this).parent().find('.edit_shipping_address').attr('href', '#').hide();
				$(this).parent().find('.edit_payment_address').attr('href', '#').hide();
				return;
			}

			$(this).parent().find('.edit_shipping_address').attr('href', '<?=$config['dir'] ?>index.php?fuseaction=user.editAuthorizeShippingProfile&shipping_profile_id='+$(this).val()+'&ajax=1').show();
			$(this).parent().find('.edit_payment_address').attr('href', '<?=$config['dir'] ?>index.php?fuseaction=user.editAuthorizePaymentProfile&payment_profile_id='+$(this).val()+'&ajax=1').show();

			var country = $.trim(delivery_addresses[val].ctry).toLowerCase();
			var haystack = ['usa','us','united states','united states america','united states of america'];
			var found = false;
			for(key in haystack)
				if(haystack[key] == country)
				{
					found = true;
					break;
				}
			if(!found)
			{
				$(this).after('<label class="error" for="'+$(this).attr('id')+'" generated="true">Please note that we ship only within United States.</label>');
				e.stopImmediatePropagation();
			}
		});
	});
/* ]]> */
</script>
<script language="javascript" type="text/javascript">
/* <![CDATA[ */

	var delivery_addresses = [];
	<?
		foreach($delivery_addresses as $customerAddressId=>$addr)
            echo "\n".'delivery_addresses['.$customerAddressId.'] = '.json_encode($addr).';';
	?>

	var billing_addresses = [];
	<?
		foreach($billing_addresses as $customerPaymentProfileId=>$addr)
			echo "\n".'billing_addresses['.$customerPaymentProfileId.'] = '.json_encode($addr).';';
	?>

	$(document).ready(function(){
		$(".edit_payment_address").fancybox({
		    'width'				: 450
			,'height'			: 505
			,'type'				: 'iframe'
			,'modal'			: false
            ,'padding'			: 0
            ,'overlayColor'		: '#CCCCCC'
            , onStart: function(){
                $('#fancybox-close').css({top: '10px', right: '10px', width: '15px', height: '15px', background: '#CCCCCC url("'+config_dir+'layout/templates/mayfair/css/fancybox/close.gif") no-repeat scroll'});
            }
			,onClosed: function(){
				window.location.reload(true);
			}
		});

		$(".edit_shipping_address").fancybox({
		    'width'				: 385
			,'height'			: 360
			,'type'				: 'iframe'
			,'modal'			: false
            ,'padding'			: 0
            ,'overlayColor'		: '#CCCCCC'
            , onStart: function(){
                $('#fancybox-close').css({top: '10px', right: '10px', width: '15px', height: '15px', background: '#CCCCCC url("'+config_dir+'layout/templates/mayfair/css/fancybox/close.gif") no-repeat scroll'});
            }
			,onClosed: function(){
				window.location.reload(true);
			}
		});

		$(".select_address").change(function(){
			if($.trim($(this).val()) != '')
			{
				$(this).parents('.detail-section').find('.input_name').val(window[$(this).attr('rel')][$(this).val()].name);
				$(this).parents('.detail-section').find('.input_phone').val(window[$(this).attr('rel')][$(this).val()].phone);
				$(this).parents('.detail-section').find('.input_line1').val(window[$(this).attr('rel')][$(this).val()].line1);
				$(this).parents('.detail-section').find('.input_line4').val(window[$(this).attr('rel')][$(this).val()].line4);
				$(this).parents('.detail-section').find('.input_postcode').val(window[$(this).attr('rel')][$(this).val()].postcode);
				$(this).parents('.detail-section').find('.input_country').val(window[$(this).attr('rel')][$(this).val()].country);
				$(this).parents('.detail-section').find('.input_country_id').val(window[$(this).attr('rel')][$(this).val()].country_id);
			}
			else
			{
				$(this).parents('.detail-section').find('.input_name').val('');
				$(this).parents('.detail-section').find('.input_phone').val('');
				$(this).parents('.detail-section').find('.input_line1').val('');
				$(this).parents('.detail-section').find('.input_line4').val('');
				$(this).parents('.detail-section').find('.input_postcode').val('');
				$(this).parents('.detail-section').find('.input_country').val('');
				$(this).parents('.detail-section').find('.input_country_id').val('');
			}
		}).change();
	});
/* ]]> */
</script>
<? $elems->placeholder('script')->captureEnd() ?>