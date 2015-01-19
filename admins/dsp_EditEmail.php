<script type="text/javascript" src="<?=$config['dir'] ?>VLib/js/validator.js"></script>
<script language="javascript" type="text/javascript">
/* <![CDATA[ */
	$(document).ready(function(){
		$('#add').click(add_description);
		$('#variable, #description').keydown(function(event){
			if(event.keyCode == 13)
			{
				add_description();
				return false;
			}
		});
		$('.remove_row').click(function(){
			$(this).parent().parent().remove();
			return false;
		});
	});

	function validatedescription()
	{
		$('#variable, #description').removeClass('error').next('label.error').hide();
		var validation = new Validator(function(errors){
			var error = '';
			for(i=0;i<errors.length;i++)
			{
				$(errors[i].dom).addClass('error');
				var label = $(errors[i].dom).next('label.error');
				if(label.length)
					label.text(errors[i].errorMsg).show();
				else
					$(errors[i].dom).after('<label class="error">'+errors[i].errorMsg+'</label>');
			}
		});

		validation.addField('variable', "variable", 'required');
		
		if(!validation.validate())
			return false;

		return true;
	}
	
	function add_description(){
		var variable = jQuery.trim($('#variable').val());
		var description = jQuery.trim($('#description').val());
		
		if(!validatedescription())
			return false;
			
		var found = false;
		$('.hidden_variable').each(function(i){
			var hidden_val = jQuery.trim($(this).val());
			if(variable == hidden_val)
				found = true;
		});
		if(found)
		{
			$('#variable, #description').val('');
			return false;
		}
		var last_id = $('tr:last', '#table_variables').attr('id');
		var next_id;
		if(last_id != undefined)
			next_id = parseInt(last_id) + 1;
		else
			next_id = 1;
		var row_class = (next_id % 2)?'light':'dark';
		$('#table_variables').append('<tr class="'+row_class+'" id="'+next_id+'"><td><input type="hidden" name="variables[]" value="'+variable+'" class="hidden_variable" />'+variable+'</td><td><input type="hidden" name="descriptions[]" value="'+description+'" class="hidden_description" />'+description+'</td><td class="right"><a href="#" class="remove_row"><img src="<?=$config['dir'] ?>images/admin/delete.png" width="16" height="16" alt="Remove" /></a></td></tr>');
		$('.remove_row').click(function(){
			$(this).parent().parent().remove();
			return false;
		});
		$('#variable, #description').val('');
		return false;
	}
/* ]]> */
</script>

<script src="<?=$config['dir'] ?>VLib/js/validator.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">
/* <![CDATA[ */
	function validateFRM()
	{
		$('#frmValidate input:text').removeClass('error').next('label.error').hide();
		var validation = new Validator(function(errors){
			var error = '';
			for(i=0;i<errors.length;i++)
			{
				$(errors[i].dom).addClass('error');
				var label = $(errors[i].dom).next('label.error');
				if(label.length)
					label.text(errors[i].errorMsg).show();
				else
					$(errors[i].dom).after('<label class="error">'+errors[i].errorMsg+'</label>');
			}
		});

		validation.addField('name','Name','required');
		validation.addField('subject','Subject','required');
		
		if(!validation.validate())
			return false;

		return true;
	}
	
	$(document).ready(function(){
		$('#frmValidate').submit(validateFRM);
	});
/* ]]> */
</script>

<script type="text/javascript">var wysiwyg=true;</script>
<script type="text/javascript" src="<?= $config['dir'] ?>VLib/js/lib_MultiTabs.js"></script>

<?= $validator->clientValidate(); ?>
<?= $validator->displayMessage(); ?>

<h1>Edit Email</h1>

<form method="post" id="frmValidate" action="<?= $config['dir'] ?>index.php?fuseaction=admin.editEmail&amp;email_id=<?=$email['id'] ?>&amp;act=update">
	<div id="tabs" class="form clearfix">
		<ul>
			<li><a href="#tabs-1">Details</a></li>
			<li><a href="#tabs-2">Content</a></li>
			<li><a href="#tabs-3">Variables</a></li>
		</ul>
		<div id="tabs-1">
			<div class="form-field clearfix">
				<label for="name">Name</label>
				<span><input type="text" class="text" id="name" name="name" value="<?=disp($_POST['name'], $email['name']) ?>" /></span>
			</div>
			<div class="form-field clearfix">
				<label for="to">To</label>
				<input type="text" class="text" id="to" name="to" value="<?=disp($_POST['to'], $email['to']) ?>" /><br />
			</div>
			<div class="form-field clearfix">
				<label for="cc">Cc</label>
				<input type="text" class="text" id="cc" name="cc" value="<?=disp($_POST['cc'], $email['cc']) ?>" /><br />
			</div>
			<div class="form-field clearfix">
				<label for="bcc">Bcc</label>
				<input type="text" class="text" id="bcc" name="bcc" value="<?=disp($_POST['bcc'], $email['bcc']) ?>" /><br />
			</div>
		</div>
		<div id="tabs-2">
			<div class="form-field clearfix">
				<label for="subject">Subject</label>
				<span><input type="text" class="text" id="subject" name="subject" value="<?=disp($_POST['subject'], $email['subject']) ?>" /></span>
			</div>

			<?= $wysiwyg->editor(disp($_POST['content'][0], $email['content'])) ?>
		</div>
		<div id="tabs-3">
			<div class="form-field clearfix">
				<label for="variable">Variable</label>
				<span><input type="text" class="text" id="variable" name="variable" value="" /></span>
			</div>
			<div class="form-field clearfix">
				<label for="description">Description</label>
				<input type="text" class="text" id="description" name="description" value="" /> <a href="#" id="add"><img src="<?=$config['dir'] ?>images/admin/add.png" width="16" height="16" alt="Add" /></a><br /><br />
			</div>
			
			<table class="values nocheck" id="table_variables">
				<tr>
					<th>Variable</th>
					<th>Description</th>
					<th>&nbsp;</th>
				</tr>
				<?
					foreach((array)unserialize($email['variables']) as $variable=>$description)
					{	
						$next_id++;
						if($class=="light")
							$class="dark";
						else
							$class="light";
							
						echo "
						<tr class=\"$class\" id=\"$next_id\">
							<td>
								<input type=\"hidden\" name=\"variables[]\" value=\"{$variable}\" class=\"hidden_variable\" /> {$variable}
							</td>
							<td>
								<input type=\"hidden\" name=\"descriptions[]\" value=\"{$description}\" class=\"hidden_description\" /> {$description}
							</td>
							<td class=\"right\">
								<a href=\"#\" class=\"remove_row\"><img src=\"{$config['dir']}images/admin/delete.png\" width=\"16\" height=\"16\" alt=\"Remove\" /></a>
							</td>
						</tr>";
					}
				?>
			</table>
		</div>
		<div class="tab-panel-buttons clearfix">
			<span class="button button-small submit">
				<input class="submit" type="submit" value="Continue" />
			</span>
			<a class="button button-grey" href="<?= $config['dir'] ?>index.php?fuseaction=admin.emails"><span>Cancel</span></a>
		</div>
	</div>
</form>
