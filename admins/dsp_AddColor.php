<link rel="stylesheet" media="screen" type="text/css" href="<?=$config['dir'] ?>lib/colorpicker/css/colorpicker.css" />
<script type="text/javascript" src="<?=$config['dir'] ?>lib/colorpicker/js/colorpicker.js"></script>

<script type="text/javascript">
/*<![CDATA[*/
$(document).ready(function(){
	$('#hexa').ColorPicker({
		onSubmit: function(hsb, hex, rgb, el) {
			$(el).val(hex);
			$(el).ColorPickerHide();
		},
		onBeforeShow: function () {
			$(this).ColorPickerSetColor(this.value);
		}
	});
});
/*]]>*/
</script>

<script type="text/javascript" src="<?=$config['dir'] ?>VLib/js/validator.js"></script>
<script language="javascript" type="text/javascript">
/* <![CDATA[ */
	function validateFRM()
	{
		$('#frmForm input:text').removeClass('error').next('label.error').hide();
		$('.ui-tabs .ui-tabs-nav li a').css('color','#333333');
		var validation = new Validator(function(errors){
			var error = '';
			for(i=0;i<errors.length;i++)
			{
				$(errors[i].dom).addClass('error');
				var label = $(errors[i].dom).next('label.error');
				if(label.length)
					label.text(errors[i].errorMsg).show();
				else
				{
					if($(errors[i].dom).attr('type') == 'file')
						$(errors[i].dom).after('<label class="error" style="width: 136px;">'+errors[i].errorMsg+'</label>');
					else
						$(errors[i].dom).after('<label class="error">'+errors[i].errorMsg+'</label>');
				}
				
				var tab_id = $(errors[i].dom).parents('.ui-tabs-panel').attr('id');
				$('.ui-tabs-nav a[href="#'+tab_id+'"]').css('color','#E64825');
			}
		});

		validation.addField('name','Name','required');
		validation.addField('code','Code','required');
		//validation.addField('hexa','Color','required');
		
		if(!validation.validate())
			return false;

		return true;
	}
	
	$(document).ready(function(){
		$('#frmForm').submit(validateFRM);
	});
/* ]]> */
</script>

<form id="postback" method="post" action="none"></form>
<h1>New Color</h1>

<?=$validator->displayMessage() ?>

<form id="frmForm" enctype="multipart/form-data" method="post" action="<?= $config['dir'] ?>index.php?fuseaction=admin.addColor&amp;act=save">
	<div id="tabs" class="form clearfix">
		<ul>
			<li><a href="#tabs-1">Details</a></li>
		</ul>
		<div id="tabs-1">
			<div class="form-field clearfix">
				<label for="name">Name<em>(used on the site)</em></label>
				<input type="text" id="name" name="name" value="" />
			</div>
			<div class="form-field clearfix">
				<label for="code">Code<em>(used on the CSV file)</em></label>
				<input type="text" id="code" name="code" value="" />
			</div>
			<div class="form-field clearfix">
				<label for="hexa">Color<em>(hex code, ie f3g4g5)</em></label>
				<input type="text" id="hexa" name="hexa" value="" />
			</div>
			<div class="form-field clearfix">
				<label for="image">Image 20 x 20<em>(jpg, gif or png only)</em></label>
				<input type="file" id="image" name="image" /><br/>
			</div>
		</div>
	</div>
	<div class="tab-panel-buttons clearfix">
		<span class="button button-small submit">
			<input class="submit" type="submit" value="Continue" />
		</span>
		<a class="button button-grey" href="<?= $config['dir'] ?>index.php?fuseaction=admin.colors"><span>Cancel</span></a>
	</div>
</form>		