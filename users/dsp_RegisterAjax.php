<?
	$page = $elems->qry_Page(20);
?>
<style type="text/css">
    html { background-color: #B6985C; }
    fieldset.login { overflow: visible; }
</style>
<div class="preview-wrap loginDetails">
    <div class="tab-box">
        <blockquote>
            <div class="loginInfo tabLink activeLink"> <a href="#" class="tabLink activeLink" id="cont-1">DETAILS</a> <a href="#" class="tabLink " id="cont-2">SIGN UP</a> </div>
        </blockquote>
    </div>
    <div class="popup">
        <form method="post" action="<?=$config['dir'] ?>register?act=save&amp;ajax=1" id="frm">
            <input type="hidden" name="is_post" value="1"/>
            <? if($_REQUEST['redirect_url']): ?>
            <input type="hidden" name="redirect_url" value="<?= $_REQUEST['redirect_url'] ?>" />
            <? endif;?>

            <?=$page['content'] ?>
            <?=$validator->displayMessage() ?>
            <fieldset class="login">
                <ul>
                    <li>
                        <label for="firstname">FIRST NAME*:</label>
                        <input type="text" class="input-field" value="<?=$_REQUEST['firstname'] ?>" name="firstname" id="firstname" />
                        <div class="clear"></div>
                    </li>
                    <li>
                        <label for="lastname">LAST NAME*:</label>
                        <input type="text" class="input-field" value="<?=$_REQUEST['lastname'] ?>" name="lastname" id="lastname" />
                        <div class="clear"></div>
                    </li>
                    <li>
                        <label for="dob">DATE OF BIRTH:</label>
                        <input type="text" class="input-field clearable" value="<?=$_REQUEST['dob'] ?>" name="dob" id="dob" placeholder="MM/DD/YYYY" />
                        <div class="clear"></div>
                    </li>
                    <li>
                        <label for="phone">PHONE*:<br /><em style="font-size: 14px; text-transform: none !important;">123-456-7890</em></label>
                        <input type="text" class="input-field" value="<?=$_REQUEST['phone'] ?>" name="phone" id="phone" />
                        <div class="clear"></div>
                    </li>
                    <li>
                        <label for="email">EMAIL*:</label>
                        <input type="text" class="input-field clearable" value="<?=$_REQUEST['email'] ?>" name="email" id="email" autocomplete="off" placeholder="required" />
                        <div class="clear"></div>
                    </li>
                    <li>
                        <label for="password">PASSWORD*:</label>
                        <input type="password" class="input-field" value="" name="password" id="password" autocomplete="off" />
                        <div class="clear"></div>
                    </li>
                    <li>
                        <label for="repeat_password">RE-TYPE PASSWORD*:</label>
                        <input type="password" class="input-field" value="" name="repeat_password" id="repeat_password" />
                        <div class="clear"></div>
                    </li>
                    <li>
                        <div class="article">
                            <p style="padding: 0;">Please send me information about new products and special events.</p>
                        </div>
                        <div class="check-box" style="margin: 0;">
                            <div class="custom-checkbox">
                                <input type="checkbox" name="newsletter" id="newsletter" value="1" <? if($_REQUEST['newsletter']): ?>checked="checked"<? endif; ?>/>
                                <label for="newsletter" class=" small-caps" style="width: auto; line-height: 5px; margin-right: 22px;">YES</label>
                            </div>
                        </div>
                        <div class="clear"></div>
                    </li>
                    <li>
                        <label>* REQUIRED</label>
                        <div class="clear"></div>
                    </li>
                </ul>
            </fieldset>
            <div class="termsConditions">
                <div class="article">
                    <h4>TERMS &amp; CONDITIONS</h4>
                    <p>By creating your account you agree to our <a href="<?=$config['dir'] ?>terms-and-conditions" target="_blank">terms and conditions</a></p>
                </div>
                <div class="check-box">
                    <div class="custom-checkbox">
                        <input type="checkbox" name="terms" id="terms" value="1">
                        <label for="terms">AGREE</label>
                    </div>
                </div>
                <div class="clear"></div>
            </div>
            <div class="block"><button class="btn green-btn fl-right omega submit" type="submit">REGISTER</button></div>
        </form>
    </div>
</div>

<? $elems->placeholder('script')->captureStart() ?>
<script language="javascript" type="text/javascript">
/* <![CDATA[ */
	function resizeFB(){
        $('#fancybox-content', parent.document).css('width', ($('#content').width())+'px');
		$('#fancybox-content', parent.document).css('height', ($('#content').height())+'px');
		parent.$.fancybox.center(true);
	}

	$(document).ready(resizeFB);
/* ]]> */
</script>
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