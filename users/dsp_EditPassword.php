<script src="<?=$config['dir'] ?>VLib/js/validator.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">
/* <![CDATA[ */
	function validateFRM()
	{
		$('#editPassword input:text, #editPassword input:password').removeClass('error').next('label.error').hide();
		
		var defaults = {
			'current_password':'Current Password'
			,'new_password':'New Password'
			,'retype_new_password':'Retype New Password'
		};
		for(var id in defaults)
		{
			if($('#'+id).val() == defaults[id])
				$('#'+id).val('');
		}
		
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

		validation.addField('current_password','Current Password','required');
		validation.addField('new_password','New Password','required');
		validation.addField('retype_new_password','Retype New Password','required');
		
		if(!validation.validate() || ($.trim($('#new_password').val()) != $.trim($('#retype_new_password').val())))
		{
			if($.trim($('#new_password').val()) != $.trim($('#retype_new_password').val()))
			{
				$('#new_password, #retype_new_password').addClass('error');
				var label = $('#new_password, #retype_new_password').next('label.error');
				var errorMsg = 'Passwords do not match';
				if(label.length)
					label.text(errorMsg).show();
				else
					$('#new_password, #retype_new_password').after('<label class="error">'+errorMsg+'</label>');
			}
			
			var x = $('#editPassword').height()+87;
			var y = $('#editPassword').width()+40;
			parent.$.colorbox.resize({width:y, height:x});

			
			for(var id in defaults)
				if($('#'+id).val() == '')
					$('#'+id).val(defaults[id]);
			return false;
		}

		return true;
	}
	
	$(document).ready(function(){
		$('#editPassword').submit(validateFRM);
	});
/* ]]> */
</script>

<script type="text/javascript">
/* <![CDATA[ */
	$(document).ready(function() {
		swapValue = [];
		$(".swap-value").each(function(i){
			 this._initType = this.type;
			 this.type = 'text';
			 switch(this._initType) {
				case 'password':
					this.value = this.title;
				break;
				case 'text':
					this.value = this.title;
				break;
			 }

			 swapValue[i] = $(this).val();
			 $(this).focus(function(){	
				$(this).val("");
				this.type = 'password';
				$(this).addClass("focus");
			 }).blur();
		});
	});
/* ]]> */
</script>


<form id="editPassword" action="<?=$config['dir'] ?>account/editPassword?act=save" method="post" class="reviewForm">
	<h3>Edit password</h3>
	<p>Change your current password</p>
	<p class="row"><span><input type="text" id="current_password" name="current_password" class="swap-value" value="" title="Current Password" /></span></p>
	<p class="row"><span><input type="text" id="new_password" name="new_password" class="swap-value" value="" title="New Password" /></span></p>
	<p class="row"><span><input type="text" id="retype_new_password" name="retype_new_password" class="swap-value" value="" title="Retype New Password" /></span></p>
	<p class="submit"><input type="submit" value="Save" class="redDoubleArrow" /><input type="button" class="redDoubleArrow ccClose" value="Close"></p>

	<div id="warning"></div>
</form>
