<link rel="stylesheet" href="<?=$config['dir'] ?>admin-assets/js/jquery.ui/css/mytheme/style.css"/>
<script type="text/javascript" src="<?=$config['dir'] ?>admin-assets/js/jquery.ui/jquery.ui.custom.js"></script>
<script type="text/javascript" src="<?=$config['dir'] ?>VLib/js/validator.js"></script>
<script language="javascript" type="text/javascript">
/* <![CDATA[ */
	var fields = [];
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
						    $this.addClass('error').after('<label class="error" for="'+$this.attr('id')+' generated ="true">"'+($this.val())+'" address already used!</label>');
						    $this.val('');	
						}
					}	
				},"json");
				
			}
		});

		$('#email').trigger('blur');
		$('input[rel="customer_type"][').live('click',function(){
		    $('input[rel="customer_type"][id!="'+$(this).attr('id')+'"]').attr('checked',false);
		});
	});
/* ]]> */
</script>
<form method="post" action="<?=$config['dir'] ?>register?act=save<? if(isset($_REQUEST['ajax'])): ?>&amp;ajax=1<? endif; ?>" class="std-form inner" id="frm" style="width: auto;">
	<?php if ($_REQUEST['redirect_url']): ?>
	<input type="hidden" name="redirect_url" value="<?php echo $_REQUEST['redirect_url']?>" />
	<?php endif;?>
	<input type="hidden" name="order_id" value="<?=$order['id']?>" />
	
	<?=$validator->displayMessage() ?>
	<?=$page['content'] ?>
	<? if($page['id'] == 24): ?>
	<p>Order number is: <?=$order['id'] ?></p>
	<? endif; ?>
	<fieldset>
		<div class="row">
			<label for="firstname">Name</label>
			<input type="text" class="text" value="<?=$_REQUEST['firstname'] ?>" name="firstname" id="firstname" />
		</div>
		<div class="row">
			<label for="dob">Date of birth <span>(dd/mm/yyyy)</span></label>
			<input type="date" class="text" value="<?=$_REQUEST['dob'] ?>" name="dob" id="dob" />
		</div>
		<div class="row">
			<label for="phone">Phone</label>
			<input type="text" class="text" value="<?=$_REQUEST['phone'] ?>" name="phone" id="phone" />
		</div>
	</fieldset>
	<fieldset>
		<div class="row">
			<label for="email">Email *</label>
			<input type="text" class="text" value="<?=$_REQUEST['email'] ?>" name="email" id="email" autocomplete="off" />
		</div>
		<div class="row">
			<label for="password">Password * <span>Min 6 characters</span></label>
			<input type="password" class="text" value="" name="password" id="password" autocomplete="off" />
		</div>
		<div class="row">
			<label for="repeat_password">Re-type password *</label>
			<input type="password" class="text" value="" name="repeat_password" id="repeat_password" />
		</div>
	</fieldset>
	<fieldset>
		<div class="row">
			<label for="student">Are you a student?</label>
			<input type="checkbox" name="student" id="student" rel='customer_type' value="1" <? if($_REQUEST['student']): ?>checked="checked"<? endif; ?>>
		</div>
		<div class="row">
			<label for="teacher">Are you a teacher?</label>
			<input type="checkbox" name="teacher" id="teacher" rel='customer_type' value="1" <? if($_REQUEST['teacher']): ?>checked="checked"<? endif; ?>>
		</div>
		<div class="row">
			<label for="shop">Are you a shop?</label>
			<input type="checkbox" name="shop" id="shop" rel='customer_type' value="1" <? if($_REQUEST['shop']): ?>checked="checked"<? endif; ?>>
		</div>
		<? if(trim($_REQUEST['additional_payment_session_id']) != ''): ?>
		<script language="javascript" type="text/javascript">
		/* <![CDATA[ */
			$(document).ready(function(){
				function additional_payment(){
					if($('#additional_payment').is(':checked'))
						$('#additional_payment_container').show();
					else
						$('#additional_payment_container').hide();
				}
				
				$('#additional_payment').click(additional_payment);
				additional_payment();
			});
		/* ]]> */
		</script>
		<div class="row">
			<input type="hidden" name="additional_payment_session_id" value="<?=$_REQUEST['additional_payment_session_id'] ?>" />
			<label for="info">Do you want to save your card details?</label>
			<input type="checkbox" name="additional_payment" id="additional_payment" value="1" <? if($_REQUEST['additional_payment']): ?>checked="checked"<? endif; ?>>
		</div>
		<div class="row" id="additional_payment_container">
			<label for="info">Please enter your card nickname (ie 'credit card' or last 4 digits of your card number)</label>
			<input type="text" class="text" name="additional_payment_label" id="additional_payment_label" value="<?=$_REQUEST['additional_payment_label'] ?>">
		</div>
		<? endif; ?>
	</fieldset>
	<fieldset>
		<div class="row">
			<span class="label">Terms &amp; Conditions</span>
			<p class="input">
				By creating your account you agree to our <a href="<?=$config['dir'] ?>terms-and-conditions" target="_blank">terms and conditions</a>
				<input type="checkbox" name="terms" id="terms" value="1">
			</p>
		</div>
	</fieldset>
	<div class="submit"><a href="#" class="btn-red submit">Register</a></div>
</form>