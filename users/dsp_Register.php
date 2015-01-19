<?
	$page = $elems->qry_Page(20);
?>
<div id="checkOut">
    <div class="block">
        <div class="tab-wrapper">
            <h1>Sign Up</h1>
            <div id="tab-content">
                <form method="post" action="<?=$config['dir'] ?>register?act=save" id="frm">
                    <input type="hidden" name="is_post" value="1"/>
                    <input type="hidden" name="return_url" value="<?=$_REQUEST['return_url'] ?>"/>

                    <?=$page['content'] ?>
                    <?=$validator->displayMessage() ?>
                    <div class="detail-section detail-section-full">
                        <div class="block">
                            <label for="firstname">First name*:</label>
                            <input type="text" class="input-box omega" value="<?=$_REQUEST['firstname'] ?>" name="firstname" id="firstname" />
                            <div class="clear"></div>
                        </div>
                        <div class="block">
                            <label for="lastname">Last name*:</label>
                            <input type="text" class="input-box omega" value="<?=$_REQUEST['lastname'] ?>" name="lastname" id="lastname" />
                            <div class="clear"></div>
                        </div>
                        <div class="block">
                            <label for="dob">Date of birth:</label>
                            <input type="text" class="input-box omega" value="<?=$_REQUEST['dob'] ?>" name="dob" id="dob" />
                            <div class="clear"></div>
                        </div>
                        <div class="block">
                            <label for="phone">Phone*:<br /><em style="font-size: 14px; text-transform: none !important;">123-456-7890</em></label>
                            <input type="text" class="input-box omega" value="<?=$_REQUEST['phone'] ?>" name="phone" id="phone" />
                            <div class="clear"></div>
                        </div>
                        <div class="block">
                            <label for="email">Email*:</label>
                            <input type="text" class="input-box omega clearable" value="<?=$_REQUEST['email'] ?>" name="email" id="email" autocomplete="off" placeholder="required" />
                            <div class="clear"></div>
                        </div>
                        <div class="block">
                            <label for="password">Password*:</span></label>
                            <input type="password" class="input-box omega" value="" name="password" id="password" autocomplete="off" />
                            <div class="clear"></div>
                        </div>
                        <div class="block">
                            <label for="repeat_password">Re-type password*:</label>
                            <input type="password" class="input-box omega" value="" name="repeat_password" id="repeat_password" />
                            <div class="clear"></div>
                        </div>
                        <div class="block top-space">
                            <div class="custom-checkbox">
                                <input type="checkbox" name="newsletter" id="newsletter" value="1" <? if($_REQUEST['newsletter']): ?>checked="checked"<? endif; ?>/>
                                <label for="newsletter" class=" small-caps">Yes, please send me information about new products and special events.</label>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <div class="block top-space">
                            <div class="custom-checkbox">
                                <input type="checkbox" name="terms" id="terms" value="1">
                                <label for="terms" class=" small-caps">By creating your account you agree to our <a href="<?=$config['dir'] ?>terms-and-conditions" target="_blank" class="golden">Terms &amp; Conditions</a></label>
                            </div>
                        </div>
                        <div class="block">
                            <label>* REQUIRED</label>
                            <div class="clear"></div>
                        </div>
                        <div class="block">
                            <a href="#" class="btn big-btn green-btn top-space submit">REGISTER</a>
                            <div class="clear"></div>
                        </div>
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
    fields[fields.length] = {'id':'firstname', 'name':"First Name", 'type':'required|custom_name'};
    fields[fields.length] = {'id':'lastname', 'name':"Last Name", 'type':'required|custom_name'};
	fields[fields.length] = {'id':'phone', 'name':"Phone", 'type':'required|custom_phone'};
	fields[fields.length] = {'id':'email', 'name':"Email", 'type':'required|email'};
	fields[fields.length] = {'id':'password', 'name':"Password", 'type':'required|password'};
	fields[fields.length] = {'id':'repeat_password', 'name':"Re-type password", 'type':'required|password'};

	function validateForm()
	{
		$('#frm input, #frm textarea').removeClass('error').removeClass('valid');
		$('#frm label.error, #frm label.valid').remove();

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
			if(!$('#'+fields[i].id+' ~ label.error').length)
				$('#'+fields[i].id).addClass('valid').after('<label class="error valid" for="'+$(this).attr('id')+'" generated="true"></label>');

		if(!ret)
			return false;

		if($.trim($('#password').val()) != $.trim($('#repeat_password').val()))
		{
			$('#password ~ label.error, #password ~ label.valid, #repeat_password ~ label.error, #repeat_password ~ label.valid').remove();
			$('#password, #repeat_password').addClass('error').after('<label class="error" for="'+$(this).attr('id')+'" generated="true">Passwords do not match</label>');
			return false;
		}

		if(!$('#terms').is(':checked'))
		{
			alert('Please agree with the terms and conditions.');
			return false;
		}

		return true;
	}

	function validateInput()
	{
		var validation = new Validator(function(errors){});
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

		var ret = validation.validateInput($(this).attr('id'));
		$(this).removeClass('error').removeClass('valid').find(' ~ label.error').remove();
		if(!ret.status)
			$(this).addClass('error').after('<label class="error" for="'+$(this).attr('id')+'" generated="true">'+ret.details.errorMsg+'</label>');
		else
			$(this).addClass('valid').after('<label class="error valid" for="'+$(this).attr('id')+'" generated="true"></label>');
	}

	$(document).ready(function(){
		$('#frm').submit(validateForm);
		$('#frm .submit').click(function(){
            $('#frm').submit();
            return false;
        });

		$('#email, #password, #repeat_password').keyup(validateInput).change(validateInput);

		$('#email').bind('blur',function(){

			var $this = $(this);
			if($(this).val().length) {
				// validate if email is not already used
				var $data =  {
					email : $(this).val()
				}
				$.post("<?=$config['dir'] ?>ajax/qry_EmailExists.php", $data, function(data){
					if(data.status == true) {
						if(data.message.total > 0) {
						    $('#email ~ label.error, #email ~ label.valid').remove();
						    $this.addClass('error').after('<label class="error" for="'+$this.attr('id')+' generated ="true">Address already used!</label>');
						    $this.val('');
						}
					}
				},"json");

			}
		}).trigger('blur');
	});
/* ]]> */
</script>
<? $elems->placeholder('script')->captureEnd() ?>