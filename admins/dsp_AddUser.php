<script language="javascript" type="text/javascript">
/* <![CDATA[ */
	$(document).ready(function(){
		//$('.edit').click(edit_address);
		//$('.remove').click(remove_address);
		//$('.save').click(update_address);
		//$('.add_address').click(add_address);
		//$('.remove').click(remove_address);
		
		var renderAddressesName = function(wrapper) {
			
			wrapper.find('.address').not('.default-address').each(function(i) {
				var address = $(this);
				address.find('.form-field').each(function(){
					var field = $(this);
					var label = field.find('label');
					var input = field.find('input');
					var textarea = field.find('textarea');
					var select = field.find('select');
					
					if( !input.length ) {
						if( !textarea.length ) {
							input = select;
						} else {
							input = textarea;
						}
					}
					
					var id = label.attr('for');
					id = id.substr(0, id.indexOf('[') ) + '['+ (i + 1) +']';
					
					label.attr('for', id);
					input.attr('id', id);
					
				});
			});
			
		}
		
		
		$('#edit-user').submit(function() {
			$('.default-address').remove();
			return true;
		});
		
		
		$('.remove-address').live('click', function(){

			var $this = $(this);
			var wrapper = $this.parents('.address-wrapper:first');
			$this.parents('.address:first').remove();
			//renderAddressesName(wrapper);
			
			return false;
		});
		
		
		$('.add_address').click(function() {
			
			var $this = $(this);
			var wrapper = $this.parents('.address-wrapper:first');
			var address = wrapper.find('.default-address').clone(true).css('display', 'block').removeClass('default-address');
			address.insertAfter( wrapper.find('.address:last') ).find('input:first').focus();
			//renderAddressesName(wrapper);
			
			return false;
		});
		
		
		
	});

	function add_address(){
		var table_id = $(this).parent().parent().parent().find('table').attr('id');
		var input_name = table_id;
		var tr_class = $('#'+table_id+' tr:last').hasClass('light')?'dark':'light';
		
		$('#'+table_id).append('<tr class="'+tr_class+'" style="display: none;"><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td class="right"><a href="#" title="Remove" class="button button-grey remove"><span>Remove</span></a><a href="#" title="Edit" class="edit button button-grey"><span>Edit</span></a></td></tr>');
		$('#'+table_id).append('<tr class="'+tr_class+' address"><td><input type="text" style="width: 100%;" name="'+input_name+'_name[]" value="" /></td><td><input type="text" style="width: 100%;" name="'+input_name+'_email[]" value="" /></td><td><input type="text" style="width: 100%;" name="'+input_name+'_phone[]" value="" /></td><td><input type="text" style="width: 100%;" name="'+input_name+'_line1[]" value="" /></td><td><input type="text" style="width: 100%;" name="'+input_name+'_line2[]" value="" /></td><td><input type="text" style="width: 100%;" name="'+input_name+'_postcode[]" value="" /></td><td><select name="'+input_name+'_country_id[]"><? foreach((array)$countries as $country) echo '<option value="'.$country['id'].'">'.$country['name'].'</option>'; ?></select></td><td class="right"><a href="#" class="button button-grey save"><span>Save</span></a></td></tr>');
		
		var tr_items = $('#'+input_name+' tr:not(:first)');
		if(tr_items.length >= 3)
			tr_items = tr_items.filter('tr:gt('+ (tr_items.length-3) +')');

		$('.edit', tr_items).click(edit_address);
		$('.remove', tr_items).click(remove_address);
		$('.save', tr_items).click(update_address);
		console.log($('.save', tr_items), tr_items);
		return false;
	}
	
	function edit_address(){
		var parent = $(this).parent().parent();
		parent.hide();
		parent.next().show();
		return false;
	}
	
	function update_address(){
		var src = $(this).parent().parent();
		var dest = src.prev();
		
		$('td:not(.right)', src).each(function(i){
			var source = $(this).children('input:text,select');
			if(source.get(0).tagName.toLowerCase() == 'input')
				$('td:eq('+i+')', dest).text(source.val());
			if(source.get(0).tagName.toLowerCase() == 'select')
				$('td:eq('+i+')', dest).text($('option:selected', source).text());
		});
		
		src.hide();
		dest.show();
		return false;
	}
	
	function remove_address(){
		var parent = $(this).parent().parent();
		parent.next().remove();
		parent.remove();
		return false;
	}
/* ]]> */
</script>

<script src="<?=$config['dir'] ?>VLib/js/validator.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">
/* <![CDATA[ */
	function validateFRM()
	{
		$('#edit-user input:text, #edit-user textarea').removeClass('error').next('label.error').hide();
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

		validation.addField('email','Email','required|email');
		validation.addField('password','Specify New Password','required');
		validation.addField('confirm','Confirm','required');
		
		if(!validation.validate() || ($.trim($('#password').val()) != $.trim($('#confirm').val())))
		{
			if($.trim($('#password').val()) != $.trim($('#confirm').val()))
			{
				$('#password, #confirm').addClass('error');
				var label = $('#password, #confirm').next('label.error');
				var errorMsg = 'Passwords do not match';
				if(label.length)
					label.text(errorMsg).show();
				else
					$('#password, #confirm').after('<label class="error">'+errorMsg+'</label>');
			}
			
			return false;
		}

		return true;
	}
	
	$(document).ready(function(){
		$('#edit-user').submit(validateFRM);
	});
/* ]]> */
</script>

<?= $validator->clientValidate(); ?>
<?= $validator->displayMessage(); ?>

<form id="postback" method="post" action="none"></form>
<h1>Add User</h1>

<form method="post" id="edit-user" action="<?= $config['dir'] ?>index.php?fuseaction=admin.addUser&amp;act=update">

	<div id="tabs" class="form clearfix">
		<ul>
			<li><a href="#tabs-1">Details</a></li>
			<li><a href="#tabs-2">Billing Address</a></li>
			<li><a href="#tabs-3">Shipping Addresses</a></li>
		</ul>
		<div id="tabs-1">
			<div class="form-field clearfix">
				<label for="email">Email</label>
				<span><input type="text" class="text" id="email" name="email" value="<?=disp($_POST['email'], '') ?>" /></span>
			</div>
			<div class="form-field clearfix">
				<label for="password">Specify New Password</label>
				<span><input id="password" type="password" class="text" name="password" autocomplete="off" /></span>
			</div>
			<div class="form-field clearfix">
				<label for="confirm">Confirm</label>
				<span><input id="confirm" type="password" class="text" name="confirm" autocomplete="off" /></span>
			</div>
			<div class="form-field clearfix">
				<label for="firstname">First Name</label>
				<input type="text" class="text" id="firstname" name="firstname" value="<?=disp($_POST['firstname'], '') ?>" /><br />	
			</div>
			<div class="form-field clearfix">
				<label for="lastname">Last Name</label>
				<input type="text" class="text" id="lastname" name="lastname" value="<?=disp($_POST['lastname'], '') ?>" /><br />
			</div>
			<div class="form-field clearfix">
				<label for="phone">Telephone</label>
				<input type="text" class="text" id="phone" name="phone" value="<?=disp($_POST['phone'], '') ?>" /><br />
			</div>
			<div class="form-field clearfix">
				<label for="dob">Date of birth</label>
				<input type="text" class="text calendar" id="dob" name="dob" value="<?=disp($_POST['dob'], '') ?>" /><br />
			</div>
			<div class="form-field clearfix">
				<label for="info">Info</label>
				<textarea class="text" id="info" name="info"><?=disp($_POST['info'], '') ?></textarea><br />
			</div>
			<div class="form-field clearfix">
				<label for="student">Student</label>
				<input type="checkbox" id="student" name="student" value="1"<? if(disp($_POST['student'], 0)): ?>checked="checked"<? endif; ?> /><br />
			</div>
			<div class="form-field clearfix">
				<label for="confirmed_student">Confirmed Student</label>
				<input type="checkbox" id="confirmed_student" name="confirmed_student" value="1"<? if(disp($_POST['confirmed_student'], 0)): ?>checked="checked"<? endif; ?> /><br />
			</div>
		</div>
		<div id="tabs-2" class="address-wrapper">
			
			<div class="right" style="margin-bottom:1em;">
				<span class="button button-small-add"><input type="button" class="add add_address" value="New Address" /></span>
			</div>
			
			<div class="default-address address" style="border:1px solid #EFEBEF;padding:5px;margin-bottom:4em;clear:both;display:none;">
				<div class="form-field clearfix">
					<label for="billing_name[%]">Name</label>
					<input type="text" class="text" id="billing_name[%]" name="billing_name[]" value="" />
				</div>
				
				<div class="form-field clearfix">
					<label for="billing_email[%]">Email</label>
					<input type="text" class="text" id="billing_email[%]" name="billing_email[]" value="" />
				</div>
				
				<div class="form-field clearfix">
					<label for="billing_phone[%]">Telephone</label>
					<input type="text" class="text" id="billing_phone[%]" name="billing_phone[]" value="" />
				</div>
				
				<div class="form-field clearfix">
					<label for="billing_line1[%]">Address Line 1</label>
					<textarea id="billing_line1[%]" name="billing_line1[]"></textarea>
				</div>
				
				<div class="form-field clearfix">
					<label for="billing_line2[%]">Address Line 2</label>
					<textarea id="billing_line2[%]" name="billing_line2[]"></textarea>
				</div>
				
				<div class="form-field clearfix">
					<label for="billing_postcode[%]">Postcode</label>
					<input type="text" class="text" id="billing_postcode[%]" name="billing_postcode[]" value="" />
				</div>
				
				<div class="form-field clearfix" style="border-bottom:none;">
					<label for="billing_country[%]">Country</label>
					<?
					
						echo "<select id=\"billing_country[{$i}]\" name=\"billing_country_id[]\">";
							foreach($countries as $country)
								echo '<option value="'.$country['id'].'">'.$country['name'].'</option>';
						echo "</select>";
					
					?>
				</div>
				
				<div class="clearfix" style="margin:1em 0;">
					<a href="#" class="button button-grey remove-address right"><span>Remove</span></a>
				</div>
			</div>
		</div>
		<div id="tabs-3" class="address-wrapper">
			
			<div class="right" style="margin-bottom:1em;">
				<span class="button button-small-add"><input type="button" class="add add_address" value="New Address" /></span>
			</div>
		
			<div class="default-address address" style="border:1px solid #EFEBEF;padding:5px;margin-bottom:4em;clear:both;display:none;">
				<div class="form-field clearfix">
					<label for="shipping_name[%]">Name</label>
					<input type="text" class="text" id="shipping_name[%]" name="shipping_name[]" value="" />
				</div>
				
				<div class="form-field clearfix">
					<label for="shipping_email[%]">Email</label>
					<input type="text" class="text" id="shipping_email[%]" name="shipping_email[]" value="" />
				</div>
				
				<div class="form-field clearfix">
					<label for="shipping_phone[%]">Telephone</label>
					<input type="text" class="text" id="shipping_phone[%]" name="shipping_phone[]" value="" />
				</div>
				
				<div class="form-field clearfix">
					<label for="shipping_line1[%]">Address Line 1</label>
					<textarea id="shipping_line1[%]" name="shipping_line1[]"></textarea>
				</div>
				
				<div class="form-field clearfix">
					<label for="shipping_line2[%]">Address Line 2</label>
					<textarea id="shipping_line2[%]" name="shipping_line2[]"></textarea>
				</div>
				
				<div class="form-field clearfix">
					<label for="shipping_postcode[%]">Postcode</label>
					<input type="text" class="text" id="shipping_postcode[%]" name="shipping_postcode[]" value="" />
				</div>
				
				<div class="form-field clearfix" style="border-bottom:none;">
					<label for="shipping_country[%]">Country</label>
					<?
					
						echo "<select id=\"shipping_country[{$i}]\" name=\"shipping_country_id[]\">";
							foreach($countries as $country)
								echo '<option value="'.$country['id'].'">'.$country['name'].'</option>';
						echo "</select>";
					
					?>
				</div>
				
				<div class="clearfix" style="margin:1em 0;">
					<a href="#" class="button button-grey remove-address right"><span>Remove</span></a>
				</div>
			</div>
			
		</div>
	</div>

	<div class="tab-panel-buttons clearfix">
		<span class="button button-small submit">
			<input class="submit" type="submit" value="Continue" />
		</span>
		<a class="button button-grey" href="<?= $config['dir'] ?>index.php?fuseaction=admin.users">
			<span>Cancel</button>
		</a>
	</div>

</form>