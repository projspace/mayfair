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
		
		if(!validation.validate())
			return false;

		return true;
	}
	
	$(document).ready(function(){
		$('#frmValidate').submit(validateFRM);
	});
/* ]]> */
</script>

<?= $validator->clientValidate(); ?>
<?= $validator->displayMessage(); ?>

<h1>Edit Content</h1>
<form method="post" id="frmValidate" action="<?= $config['dir'] ?>index.php?fuseaction=admin.editContentArea&act=update&area_id=<?=$content_area['id']?>">
	<div id="tabs" class="form clearfix">
		<ul>
			<li><a href="#tabs-1">Details</a></li>
			<li><a href="#tabs-2">Content</a></li>
		</ul>
		<div id="tabs-1">
			<div class="form-field clearfix">
				<label for="name">Name:</label>				
				<span><input type="text" id="name" class="text" name="name" value="<?=disp($_POST['name'], $content_area['name']) ?>" /></span>
			</div>
		</div>
		<div id="tabs-2">
			<?=$wysiwyg->editor(disp($_POST['content'][0], $content_area['description'])) ?>
		</div>
		
	</div>


	<div class="tab-panel-buttons clearfix">
		<span class="button button-small submit">
			<input class="submit" type="submit" value="Continue" />
		</span>
		<a class="button button-grey" href="<?= $config['dir'] ?>index.php?fuseaction=admin.contentAreas">
			<span>Cancel</button>
		</a>
	</div>
</form>