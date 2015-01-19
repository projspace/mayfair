<?
	$page = $elems->qry_Page(30);
	$countries = $countries->GetRows();
?>
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
                <form method="post" action="<?=$config['dir'] ?>checkout?act=login" id='addrFormLogin' style="padding-bottom: 0;">
					<input type="hidden" value="<?=$config['dir'] ?>checkout" name="return_url">
					<?=$login_validator->displayMessage() ?>
					<?=$page['content'] ?>
                    <div class="detail-section detail-section-full">
                        <div class="block">
                            <label for="login_email">email address</label>
                            <input type="text" class="input-box clearable omega" id="login_email" name="email" value="" placeholder="required" />
                            <div class="clear"></div>
                        </div>
                        <div class="block">
                            <label for="login_password">password:</label>
                            <input type="password" class="input-box clearable omega" id="login_password" name="password" value="" />
                            <a href="#" class="btn big-btn green-btn submit">LOG IN</a>
                            <div class="clear"></div>
                        </div>
                    </div>
				</form>

                <form method="post" action="<?=$config['dir'] ?>checkout?act=saveDetails" class="std-form inner" id='addrForm'>
                    <div class="contactForm">
                        <h3 class="capital grey clear alt">Express checkout <span class="alt">You can set up an account later</span></h3>
                        <?=$validator->displayMessage() ?>
                        <div class="detail-section">
                            <h3 class="capital">Billing address</h3>
                            
                            <div class="block">
                                <label for="billing_name">Name*</label>
                                <input type="text" class="input-box clearable input_name" value="<?=disp($_POST['billing_name'], $billing['name']) ?>" placeholder="required" name="billing_name" id="billing_name" />
                                <div class="clear"></div>
                            </div>
                            <div class="block">
                                <label for="billing_email">Email*</label>
                                <input type="text" class="input-box clearable input_email" value="<?=disp($_POST['billing_email'], $billing['email']) ?>" placeholder="required" name="billing_email" id="billing_email" />
                                <div class="clear"></div>
                            </div>
                            <div class="block">
                                <label for="billing_phone">Phone*<br /><em style="font-size: 14px; text-transform: none !important;">123-456-7890</em></label>
                                <input type="text" class="input-box clearable input_phone" value="<?=disp($_POST['billing_phone'], $billing['phone']) ?>" placeholder="required" name="billing_phone" id="billing_phone" />
                                <div class="clear"></div>
                            </div>
                            <div class="block">
                                <label for="billing_line1">Address 1*</label>
                                <input type="text" class="input-box clearable input_line1" value="<?=disp($_POST['billing_line1'], $billing['line1']) ?>" placeholder="required" name="billing_line1" id="billing_line1" />
                                <div class="clear"></div>
                            </div>
                            <div class="block">
                                <label for="billing_line2">Address 2</label>
                                <input type="text" class="input-box clearable input_line2" value="<?=disp($_POST['billing_line2'], $billing['line2']) ?>" name="billing_line2" id="billing_line2" />
                                <div class="clear"></div>
                            </div>
                            <div class="block">
                                <label for="billing_line3">Address 3</label>
                                <input type="text" class="input-box clearable input_line3" value="<?=disp($_POST['billing_line3'], $billing['line3']) ?>" name="billing_line3" id="billing_line3" />
                                <div class="clear"></div>
                            </div>
                            <div class="block">
                                <label for="billing_line4">City*</label>
                                <input type="text" class="input-box clearable input_line4" value="<?=disp($_POST['billing_line4'], $billing['line4']) ?>" placeholder="required" name="billing_line4" id="billing_line4" />
                                <div class="clear"></div>
                            </div>
                            <div class="block">
                                <label for="billing_postcode">Zip code*</label>
                                <input type="text" class="input-box clearable input_postcode" value="<?=disp($_POST['billing_postcode'], $billing['postcode']) ?>" placeholder="required" name="billing_postcode" id="billing_postcode" />
                                <div class="clear"></div>
                            </div>
                            <div class="block">
                                <label for="billing_country_id">State</label>
                                <div class="report-box custom-select text-big">
                                    <select class="input_country_id styled" id="billing_country_id" name="billing_country_id">
                                    <?
                                        $country_id = disp($_POST['billing_country_id'], ($session->session->fields['billing_country_id']+0)?:$config['defaultcountry_id']);

                                        foreach($countries as $row)
                                            if($country_id == $row['id'])
                                                echo '<option value="'.$row['id'].'" selected="selected">'.htmlentities($row['name'], ENT_NOQUOTES, 'UTF-8').'</option>';
                                            else
                                                echo '<option value="'.$row['id'].'">'.htmlentities($row['name'], ENT_NOQUOTES, 'UTF-8').'</option>';
                                    ?>
                                    </select>
                                </div>
                                <div class="clear"></div>
                            </div>
                            <div class="block">
                                <label>* REQUIRED</label>
                                <? if($session->session->fields['last_gift_list_id']+0): ?>
                                <br clear="all"/>
                                <p style="margin-top: 10px;">mayfairhouse.com is happy to provide complimentary ground shipping to the address specified by the gift registrant.</p>
                                <a href="#" class="btn big-btn green-btn top-space right-space submit">PAYMENT</a>
                                <? endif; ?>
                                <div class="clear"></div>
                            </div>
                        </div>
                        <? if(!$session->session->fields['last_gift_list_id']+0): ?>
                        <div class="detail-section">
                            <h3 class="capital">DELIVERY address</h3>

                            <div class="custom-checkbox-alt">
                                <label for="same_delivery">Same as billing address</label>
                                <input type="checkbox" name="same_delivery" id="same_delivery" value="1" />
                            </div>
                            <div class="block">
                                <label for="delivery_name">Name*</label>
                                <input type="text" class="input-box clearable input_name" value="<?=disp($_POST['delivery_name'], $delivery['name']) ?>" placeholder="required" name="delivery_name" id="delivery_name" />
                                <div class="clear"></div>
                            </div>
                            <div class="block">
                                <label for="delivery_email">Email*</label>
                                <input type="text" class="input-box clearable input_email" value="<?=disp($_POST['delivery_email'], $delivery['email']) ?>" placeholder="required" name="delivery_email" id="delivery_email" />
                                <div class="clear"></div>
                            </div>
                            <div class="block">
                                <label for="delivery_phone">Phone*<br /><em style="font-size: 14px; text-transform: none !important;">123-456-7890</em></label>
                                <input type="text" class="input-box clearable input_phone" value="<?=disp($_POST['delivery_phone'], $delivery['phone']) ?>" placeholder="required" name="delivery_phone" id="delivery_phone" />
                                <div class="clear"></div>
                            </div>
                            <div class="block">
                                <label for="delivery_line1">Address 1*</label>
                                <input type="text" class="input-box clearable input_line1" value="<?=disp($_POST['delivery_line1'], $delivery['line1']) ?>" placeholder="required" name="delivery_line1" id="delivery_line1" />
                                <div class="clear"></div>
                            </div>
                            <div class="block">
                                <label for="delivery_line2">Address 2</label>
                                <input type="text" class="input-box clearable input_line2" value="<?=disp($_POST['delivery_line2'], $delivery['line2']) ?>" name="delivery_line2" id="delivery_line2" />
                                <div class="clear"></div>
                            </div>
                            <div class="block">
                                <label for="delivery_line3">Address 3</label>
                                <input type="text" class="input-box clearable input_line3" value="<?=disp($_POST['delivery_line3'], $delivery['line3']) ?>" name="delivery_line3" id="delivery_line3" />
                                <div class="clear"></div>
                            </div>
                            <div class="block">
                                <label for="delivery_line4">City*</label>
                                <input type="text" class="input-box clearable input_line4" value="<?=disp($_POST['delivery_line4'], $delivery['line4']) ?>" placeholder="required" name="delivery_line4" id="delivery_line4" />
                                <div class="clear"></div>
                            </div>
                            <div class="block">
                                <label for="delivery_postcode">Zip code*</label>
                                <input type="text" class="input-box clearable input_postcode" value="<?=disp($_POST['delivery_postcode'], $delivery['postcode']) ?>" placeholder="required" name="delivery_postcode" id="delivery_postcode" />
                                <div class="clear"></div>
                            </div>
                            <div class="block">
                                <label for="delivery_country_id">State</label>
                                <div class="report-box custom-select text-big">
                                    <select class="input_country_id styled" id="delivery_country_id" name="delivery_country_id">
                                    <?
                                        foreach($countries as $row)
                                            if($session->session->fields['delivery_country_id']+0 == $row['id'])
                                                echo '<option value="'.$row['id'].'" selected="selected">'.htmlentities($row['name'], ENT_NOQUOTES, 'UTF-8').'</option>';
                                            else
                                                echo '<option value="'.$row['id'].'">'.htmlentities($row['name'], ENT_NOQUOTES, 'UTF-8').'</option>';
                                    ?>
                                    </select>
                                </div>
                                <div class="clear"></div>
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
	fields[fields.length] = {'id':'billing_name', 'name':"Name", 'type':'required|custom_name'};
	fields[fields.length] = {'id':'billing_email', 'name':"Email", 'type':'required|email'};
	fields[fields.length] = {'id':'billing_phone', 'name':"Phone", 'type':'required|custom_phone'};
	fields[fields.length] = {'id':'billing_line1', 'name':"Address Line 1", 'type':'required'};
	fields[fields.length] = {'id':'billing_line4', 'name':"Address Line 4", 'type':'required'};
	fields[fields.length] = {'id':'billing_postcode', 'name':"Postcode", 'type':'required|postcode'};
	fields[fields.length] = {'id':'billing_country_id', 'name':"Country", 'type':'required'};

	<? if(!$session->session->fields['last_gift_list_id']+0): ?>
	fields[fields.length] = {'id':'delivery_name', 'name':"Name", 'type':'required|custom_name'};
	fields[fields.length] = {'id':'delivery_email', 'name':"Email", 'type':'required|email'};
	fields[fields.length] = {'id':'delivery_phone', 'name':"Phone", 'type':'required|custom_phone'};
	fields[fields.length] = {'id':'delivery_line1', 'name':"Address Line 1", 'type':'required'};
	fields[fields.length] = {'id':'delivery_line4', 'name':"Address Line 4", 'type':'required'};
	fields[fields.length] = {'id':'delivery_postcode', 'name':"Postcode", 'type':'required|postcode'};
	fields[fields.length] = {'id':'delivery_country_id', 'name':"Country", 'type':'required'};
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
        validation.addFieldType(
			'custom_name'
			, function(){
				if(jQuery.trim($(this).val()) == '')
					return true;
				if(jQuery.trim($(this).val()).length >= <?= GIFT_NAME_MIN_LENGTH+0 ?>)
					return true;
				else
					return false;
			}
			, 'CE1'
			, 'Value must be at least <?= GIFT_NAME_MIN_LENGTH+0 ?> characters.'
		);
		validation.addFieldType(
			'custom_phone'
			, function(){
				var phone = jQuery.trim($(this).val());
				if(!phone) {
					return true;
				}
				
				if( phone.match(/^([^-\(\)]){10}$/) ) { //math xxxxxxxxxx
					return true;
				}
				
				if( phone.match(/^.{3}-.{3}-.{4}$/) ) { //math xxx-xxx-xxxx
					return true;
				}
				
				if( phone.match(/^(\()+.{3}\) .{3}-.{4}$/) ) { //math (xxx) xxx-xxxx
					return true;
				}

				return false;
			}
			, 'CE2'
            //, 'Value must have at least <?= GIFT_PHONE_MIN_DIGITS+0 ?> digits.'
			, 'Accepted formats: 123-456-7890 <br/>OR (123) 456-7890 OR 1234567890'
		);
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
        validation.addFieldType(
			'custom_name'
			, function(){
				if(jQuery.trim($(this).val()) == '')
					return true;
				if(jQuery.trim($(this).val()).length >= <?= GIFT_NAME_MIN_LENGTH+0 ?>)
					return true;
				else
					return false;
			}
			, 'CE1'
			, 'Value must be at least <?= GIFT_NAME_MIN_LENGTH+0 ?> characters.'
		);
		validation.addFieldType(
			'custom_phone'
			, function(){
				var phone = jQuery.trim($(this).val());
				if(!phone) {
					return true;
				}
				
				if( phone.match(/^([^-\(\)]){10}$/) ) { //math xxxxxxxxxx
					return true;
				}
				
				if( phone.match(/^.{3}-.{3}-.{4}$/) ) { //math xxx-xxx-xxxx
					return true;
				}
				
				if( phone.match(/^(\()+.{3}\) .{3}-.{4}$/) ) { //math (xxx) xxx-xxxx
					return true;
				}

				return false;
			}
			, 'CE2'
            //, 'Value must have at least <?= GIFT_PHONE_MIN_DIGITS+0 ?> digits.'
			, 'Accepted formats: 123-456-7890 <br/>OR (123) 456-7890 OR 1234567890'
		);
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
	});
/* ]]> */
</script>
<script language="javascript" type="text/javascript">
/* <![CDATA[ */
	function same_delivery(){
		if($('#same_delivery').is(':checked'))
		{
			var delivery_id = $(this).attr('id').split('billing').join('delivery');
			$('#'+delivery_id).val($(this).val());

			if(delivery_id == 'delivery_country_id')
				$('#delivery_country_id').trigger('update');
		}
	}

	var addresses = [];
	<?
		foreach($addresses as $row)
		{
			echo 'addresses['.$row['id'].'] = {
				"name": "'.addcslashes($row['name'], '"').'"
				,"email": "'.addcslashes($row['email'], '"').'"
				,"phone": "'.addcslashes($row['phone'], '"').'"
				,"line1": "'.addcslashes(str_replace(array("\r\n","\n", "\r"), " ",$row['line1']), '"').'"
				,"line2": "'.addcslashes($row['line2'], '"').'"
				,"line3": "'.addcslashes($row['line3'], '"').'"
				,"line4": "'.addcslashes($row['line4'], '"').'"
				,"postcode": "'.addcslashes($row['postcode'], '"').'"
				,"country_id": "'.addcslashes($row['country_id'], '"').'"
			};';
		}
	?>

	$(document).ready(function(){
		$(".select_address").change(function(){
			$(this).parents('fieldset').find('.input_name').val(addresses[$(this).val()].name);
			$(this).parents('fieldset').find('.input_email').val(addresses[$(this).val()].email);
			$(this).parents('fieldset').find('.input_phone').val(addresses[$(this).val()].phone);
			$(this).parents('fieldset').find('.input_line1').val(addresses[$(this).val()].line1);
			$(this).parents('fieldset').find('.input_line2').val(addresses[$(this).val()].line2);
			$(this).parents('fieldset').find('.input_line3').val(addresses[$(this).val()].line3);
			$(this).parents('fieldset').find('.input_line4').val(addresses[$(this).val()].line4);
			$(this).parents('fieldset').find('.input_postcode').val(addresses[$(this).val()].postcode);
			var id = $(this).parents('fieldset').find('.input_country_id').attr('id');
			$('#'+id).val(addresses[$(this).val()].country_id);
			$('#'+id).parent().find('a.selectBox span.selectBox-label').html($('option:selected', '#'+id).html());
			var select_index = $('select#'+id).index();
			var index = $('option:selected', '#'+id).index();
			$('ul.selectBox-dropdown-menu:eq('+select_index+') li').removeClass('selectBox-selected');
			$('ul.selectBox-dropdown-menu:eq('+select_index+') li:eq('+index+')').addClass('selectBox-selected');
		});

		$('#same_delivery').click(function(){
			if($(this).is(':checked'))
			{
				$('#delivery_name').val($('#billing_name').val()).attr('readonly','readonly');
				$('#delivery_email').val($('#billing_email').val()).attr('readonly','readonly');
				$('#delivery_phone').val($('#billing_phone').val()).attr('readonly','readonly');
				$('#delivery_line1').val($('#billing_line1').val()).attr('readonly','readonly');
				$('#delivery_line2').val($('#billing_line2').val()).attr('readonly','readonly');
				$('#delivery_line3').val($('#billing_line3').val()).attr('readonly','readonly');
				$('#delivery_line4').val($('#billing_line4').val()).attr('readonly','readonly');
				$('#delivery_postcode').val($('#billing_postcode').val()).attr('readonly','readonly');
				$('#delivery_country_id').val($('#billing_country_id').val()).attr('readonly','readonly');
                $('#delivery_country_id').trigger('update');

				/*$('#delivery_country_id').parent().find('a.selectBox span.selectBox-label').html($('#billing_country_id').parent().find('a.selectBox span.selectBox-label').html());
				var select_index = $('select#delivery_country_id').index();
				$('ul.selectBox-dropdown-menu:eq('+select_index+') li').removeClass('selectBox-selected');
				$('ul.selectBox-dropdown-menu:eq('+select_index+') li a[rel="'+$('#billing_country_id').val()+'"]').closest('li').addClass('selectBox-selected');*/
			}
			else
			{
				$('#delivery_name').removeAttr('readonly');
				$('#delivery_email').removeAttr('readonly');
				$('#delivery_phone').removeAttr('readonly');
				$('#delivery_line1').removeAttr('readonly');
				$('#delivery_line2').removeAttr('readonly');
				$('#delivery_line3').removeAttr('readonly');
				$('#delivery_line4').removeAttr('readonly');
				$('#delivery_postcode').removeAttr('readonly');
				$('#delivery_country_id').removeAttr('readonly');
			}
		});



		$("#billing_name, #billing_email, #billing_phone, #billing_line1, #billing_line2, #billing_line3, #billing_line4, #billing_postcode").keyup(same_delivery).change(same_delivery);
		$('#billing_country_id').change(same_delivery);
		$('#billing_id, #delivery_id').change();
	});
/* ]]> */
</script>

<script language="javascript" type="text/javascript">
/* <![CDATA[ */
    var login_fields = [];
    login_fields[login_fields.length] = {'id':'login_email', 'name':"Email", 'type':'required|email'};
    login_fields[login_fields.length] = {'id':'login_password', 'name':"Password", 'type':'required|password'};

    function validateLoginFrm()
    {
        $('#addrFormLogin input, #addrFormLogin textarea').removeClass('error').removeClass('valid');
        $('#addrFormLogin label.error, #addrFormLogin label.valid').remove();

        for(i=0;i<login_fields.length;i++)
            if($('#'+login_fields[i].id).val() == $('#'+login_fields[i].id).attr('placeholder'))
                $('#'+login_fields[i].id).val('');

        var validation = new Validator(function(errors){
            for(i=0;i<errors.length;i++)
            {
                $(errors[i].dom).addClass('error').after('<label class="error" for="'+$(this).attr('id')+'" generated="true">'+errors[i].errorMsg+'</label>');
            }
        });

        for(i=0;i<login_fields.length;i++)
            validation.addField(login_fields[i].id, login_fields[i].name, login_fields[i].type);

        var ret = validation.validate();

        for(i=0;i<login_fields.length;i++)
        {
            if(!$('#'+login_fields[i].id+' ~ label.error').length)
                $('#'+login_fields[i].id).addClass('valid').after('<label class="error valid" for="'+$(this).attr('id')+'" generated="true"></label>');

            if($('#'+login_fields[i].id).val() == '')
                $('#'+login_fields[i].id).val($('#'+login_fields[i].id).attr('placeholder'));
        }
        return ret;
    }

    function validateLoginInput()
    {
        var validation = new Validator(function(errors){});
        for(i=0;i<login_fields.length;i++)
            validation.addField(login_fields[i].id, login_fields[i].name, login_fields[i].type);

        var ret = validation.validateInput($(this).attr('id'));
        $(this).removeClass('error').removeClass('valid').find(' ~ label.error').remove();
        if(!ret.status)
            $(this).addClass('error').after('<label class="error" for="'+$(this).attr('id')+'" generated="true">'+ret.details.errorMsg+'</label>');
        else
            $(this).addClass('valid').after('<label class="error valid" for="'+$(this).attr('id')+'" generated="true"></label>');
    }

    $(document).ready(function(){
        $('#addrFormLogin').submit(validateLoginFrm);
        $('#addrFormLogin .submit').click(function(){
            $('#addrFormLogin').submit();
            return false;
        });

        $('#login_email, #login_password').keyup(validateLoginInput).change(validateLoginInput);
    });
/* ]]> */
</script>
<? $elems->placeholder('script')->captureEnd() ?>