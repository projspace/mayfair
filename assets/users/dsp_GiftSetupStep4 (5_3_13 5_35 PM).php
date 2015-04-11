<link rel="stylesheet" href="<?=$config['dir'] ?>admin-assets/js/jquery.ui/css/mytheme/style.css"/>
<script type="text/javascript" src="<?=$config['dir'] ?>admin-assets/js/jquery.ui/jquery.ui.custom.js"></script>
<script type="text/javascript" src="<?=$config['dir'] ?>VLib/js/validator.js"></script>
<script language="javascript" type="text/javascript">
/* <![CDATA[ */
	var fields = [];
	fields[fields.length] = {'id':'public', 'name':"Public list", 'type':'required'};
	fields[fields.length] = {'id':'password', 'name':"Password", 'type':'required|password'};
	fields[fields.length] = {'id':'confirm_password', 'name':"Confirm Password", 'type':'required|password'};
	
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

		if($.trim($('#password').val()) != $.trim($('#confirm_password').val()))
		{
			$('#password ~ label.error, #password ~ label.valid, #confirm_password ~ label.error, #confirm_password ~ label.valid').remove();
			$('#password, #confirm_password').addClass('error').after('<label class="error" for="'+$(this).attr('id')+'" generated="true">Passwords do not match</label>');
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
		$('#password,#confirm_password').keyup(validateInput).change(validateInput);
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
				<p>Please give us the details about your list.</p>
				<fieldset>
					<div class="row">
						<label for="public">Public list</label>
						<select name="public" id="public">
							<option value="0" <? if(!disp($_REQUEST['public'], $_SESSION['gift_setup']['public'])): ?>selected="selected"<? endif; ?>>No</option>
							<option value="1" <? if(disp($_REQUEST['public'], $_SESSION['gift_setup']['public'])): ?>selected="selected"<? endif; ?>>Yes</option>
						</select>
					</div>
					<div class="row">
						<label for="password">Password</label>
						<input type="password" class="text" value="<?=disp($_REQUEST['password'], $_SESSION['gift_setup']['password']) ?>" name="password" id="password" />
					</div>
					<div class="row">
						<label for="confirm_password">Confirm Password</label>
						<input type="password" class="text" value="" name="confirm_password" id="confirm_password" />
					</div>
				</fieldset>
				<fieldset>
					<div class="row">
						<span class="label">I've read and agree with the <a href="<?= $config['dir'] ?>terms-conditions" target="_blank">Terms &amp; Conditions</a></span>
						<p class="input">
							<input type="checkbox" name="terms" id="terms" value="1">
						</p>
					</div>
				</fieldset>
				<div class="submit"><a href="#" class="btn-red submit">Next &gt;</a></div>
			</form>
		</section>
	</article>
</div>