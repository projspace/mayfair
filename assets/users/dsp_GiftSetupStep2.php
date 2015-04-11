<link rel="stylesheet" href="<?=$config['dir'] ?>admin-assets/js/jquery.ui/css/mytheme/style.css"/>
<script type="text/javascript" src="<?=$config['dir'] ?>admin-assets/js/jquery.ui/jquery.ui.custom.js"></script>
<script type="text/javascript" src="<?=$config['dir'] ?>VLib/js/validator.js"></script>
<script language="javascript" type="text/javascript">
/* <![CDATA[ */
	var fields = [];
	fields[fields.length] = {'id':'title', 'name':"Title", 'type':'required'};
	fields[fields.length] = {'id':'first_name', 'name':"First name", 'type':'required|custom_name'};
	fields[fields.length] = {'id':'middle_name', 'name':"Middle name", 'type':'custom_name'};
	fields[fields.length] = {'id':'surname', 'name':"Surname", 'type':'required|custom_name'};
	fields[fields.length] = {'id':'primary_phone', 'name':"Primary phone", 'type':'required|custom_phone'};
	fields[fields.length] = {'id':'secondary_phone', 'name':"Secondary phone", 'type':'custom_phone'};
	fields[fields.length] = {'id':'email', 'name':"Email", 'type':'required|email'};
	fields[fields.length] = {'id':'confirm_email', 'name':"Confirm Email", 'type':'required|email'};
	fields[fields.length] = {'id':'contact_method', 'name':"Preferred method", 'type':'required'};
	
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

		if($.trim($('#email').val()) != $.trim($('#confirm_email').val()))
		{
			$('#email ~ label.error, #email ~ label.valid, #confirm_email ~ label.error, #confirm_email ~ label.valid').remove();
			$('#email, #confirm_email').addClass('error').after('<label class="error" for="'+$(this).attr('id')+'" generated="true">Emails do not match</label>');
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
		$('#title,#first_name,#middle_name,#surname,#primary_phone,#secondary_phone,#email,#confirm_email').keyup(validateInput).change(validateInput);
	});
/* ]]> */
</script>
<div id="content-wrapper">
	<article id="fitting-guide">
		<header class="content-box"><h1>Gift Registry / Setup</h1></header>
		<section class="content-box">
			<form method="post" action="" class="std-form inner" id="frm" style="width: auto;">
				<input type="hidden" name="is_post" value="1"/>
				<?=$validator->displayMessage() ?>
				<p>Please give us the details on how we contact you.</p>
				<fieldset>
					<div class="row">
						<label for="title">Title</label>
						<input type="text" class="text" value="<?=disp($_REQUEST['title'], $_SESSION['gift_setup']['title']) ?>" name="title" id="title" />
					</div>
					<div class="row">
						<label for="first_name">First name</label>
						<input type="text" class="text" value="<?=disp($_REQUEST['first_name'], $_SESSION['gift_setup']['first_name']) ?>" name="first_name" id="first_name" />
					</div>
					<div class="row">
						<label for="middle_name">Middle name</label>
						<input type="text" class="text" value="<?=disp($_REQUEST['middle_name'], $_SESSION['gift_setup']['middle_name']) ?>" name="middle_name" id="middle_name" />
					</div>
					<div class="row">
						<label for="surname">Surname</label>
						<input type="text" class="text" value="<?=disp($_REQUEST['surname'], $_SESSION['gift_setup']['surname']) ?>" name="surname" id="surname" />
					</div>
					<div class="row">
						<label for="primary_phone">Primary phone</label>
						<input type="text" class="text" value="<?=disp($_REQUEST['primary_phone'], $_SESSION['gift_setup']['primary_phone']) ?>" name="primary_phone" id="primary_phone" />
					</div>
					<div class="row">
						<label for="secondary_phone">Secondary phone</label>
						<input type="text" class="text" value="<?=disp($_REQUEST['secondary_phone'], $_SESSION['gift_setup']['secondary_phone']) ?>" name="secondary_phone" id="secondary_phone" />
					</div>
					<div class="row">
						<label for="email">Email</label>
						<input type="text" class="text" value="<?=disp($_REQUEST['email'], $_SESSION['gift_setup']['email']) ?>" name="email" id="email" />
					</div>
					<div class="row">
						<label for="confirm_email">Confirm Email</label>
						<input type="text" class="text" value="" name="confirm_email" id="confirm_email" />
					</div>
					<div class="row">
						<label for="contact_method">Preferred method</label>
						<select name="contact_method" id="contact_method">
							<option value="phone" <? if('phone' == disp($_REQUEST['contact_method'], $_SESSION['gift_setup']['contact_method'])): ?>selected="selected"<? endif; ?>>Phone</option>
							<option value="email" <? if('email' == disp($_REQUEST['contact_method'], $_SESSION['gift_setup']['contact_method'])): ?>selected="selected"<? endif; ?>>Email</option>
						</select>
					</div>
				</fieldset>
				<fieldset>
					<div class="row">
						<span class="label">I'd like to receive notification about other services</span>
						<p class="input">
							<input type="checkbox" name="newsletter" id="newsletter" value="1" <? if(disp($_REQUEST['newsletter'], $_SESSION['gift_setup']['newsletter'])): ?>checked="checked"<? endif; ?>>
						</p>
					</div>
				</fieldset>
				<div class="submit"><a href="#" class="btn-red submit">Next &gt;</a></div>
			</form>
		</section>
	</article>
</div>