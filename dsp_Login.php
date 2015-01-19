<!doctype html>

<html lang="en">
<head>
	<title>Login</title>
	<meta name="keywords" content="">
	<meta name="description" content="">
	
	<? include $config['layout_path']."inc_Head.php"; ?>
</head>

<body>

<div id="page-wrapper" style="width: 520px;">
	<header id="page-header">
		<h1 id="logo"><a href="<?=$config['dir'] ?>">bloch - since 1932</a></h1>
	</header>
	
	<script type="text/javascript" src="<?=$config['dir'] ?>VLib/js/validator.js"></script>
	<script language="javascript" type="text/javascript">
	/* <![CDATA[ */
		var fields = [];
		fields[fields.length] = {'id':'email', 'name':"Email", 'type':'required|email'};
		fields[fields.length] = {'id':'password', 'name':"Password", 'type':'required|password'};
		
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
			$('#frm').submit(validateForm);
			
			$('#email, #password').keyup(validateInput).change(validateInput);
		});
	/* ]]> */
	</script>

	<div id="content-wrapper">
		<article id="fitting-guide">
			<header class="content-box"><h1>Sign in</h1></header>
			<section class="content-box">
				<form method="post" action="<?=$config['dir'] ?>login?act=login<? if(isset($_REQUEST['ajax'])): ?>&amp;ajax=1<? endif; ?>" class="std-form inner" id="frm" style="width: auto;">
					<input type="hidden" name="return_url" value="<?=$_REQUEST['return_url'] ?>"/>
					
					<?=$validator->displayMessage() ?>
					<?=$page['content'] ?>
					<fieldset class="last">
						<div class="row">
							<label for="email">Email address</label>
							<input type="text" class="text" value="" name="email" id="email" />
						</div>
						<div class="row">
							<label for="password">Password <span>Min 6 characters</span></label>
							<input type="password" class="text" value="" name="password" id="password" />
						</div>
						<p class="notice">&nbsp;</p>
						<div class="submit"><a href="#" class="btn-red submit">Login</a></div>
					</fieldset>
				</form>
			</section>
		</article>
	</div>
</div>

</body>
</html>