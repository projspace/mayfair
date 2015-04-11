<script type="text/javascript">var wysiwyg=false;</script>
<script type="text/javascript" src="<?= $config['dir'] ?>VLib/js/lib_MultiTabs.js"></script>

<script language="javascript" type="text/javascript">
/* <![CDATA[ */
	
	
	$(document).ready(function(){
		//$('.edit').click(edit_address);
		//$('.remove').click(remove_address);
		//$('.save').click(update_address);
		//$('.add_address').click(add_address);
		//$('.remove').click(remove_address);
		
		
		$('input[rel="customer_type"]').click(function(){
		    $ ( 'input[rel="customer_type"][name!="' + $(this).attr('name') + '"]' ).attr ( "checked" , false );
		    return true;
		});
		
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
<h1>Edit User</h1>

<form method="post" id="edit-user" action="<?= $config['dir'] ?>index.php?fuseaction=admin.editUser&amp;act=update">

	<div id="tabs" class="form clearfix">
		<ul>
			<li><a href="#tabs-1">Details</a></li>
			<?php if($user['teacher']+0): ?>
			<li><a href="#tabs-5">Teacher info</a></li>
			<?php endif; ?>
			<?php if($user['shop']+0): ?>
			<li><a href="#tabs-6">Shop info</a></li>
			<?php endif; ?>
			<li><a href="#tabs-2">Billing Address</a></li>
			<li><a href="#tabs-3">Shipping Addresses</a></li>
			<li><a href="#tabs-4">Discount Codes</a></li>
		</ul>
		<div id="tabs-1">
			<div class="form-field clearfix">
				<label for="email">Email</label>
				<span><input type="text" class="text" id="email" name="email" value="<?=disp($_POST['email'], $user['email']) ?>" /></span>
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
				<input type="text" class="text" id="firstname" name="firstname" value="<?=disp($_POST['firstname'], $user['firstname']) ?>" /><br />	
			</div>
			<div class="form-field clearfix">
				<label for="lastname">Last Name</label>
				<input type="text" class="text" id="lastname" name="lastname" value="<?=disp($_POST['lastname'], $user['lastname']) ?>" /><br />
			</div>
			
			
			<div class="form-field clearfix">
				<label for="phone">Telephone</label>
				<input type="text" class="text" id="phone" name="phone" value="<?=disp($_POST['phone'], $user['primary_phone']) ?>" /><br />
			</div>
			<div class="form-field clearfix">
				<label for="dob">Date of birth</label>
				<input type="text" class="text calendar" id="dob" name="dob" value="<?=disp($_POST['dob'], (($time=strtotime($user['dob'])) > 0)?date('d/m/Y', $time):'') ?>" /><br />
			</div>
			<div class="form-field clearfix">
				<label for="info">Info</label>
				<textarea class="text" id="info" name="info"><?=disp($_POST['info'], $user['info']) ?></textarea><br />
			</div>
			<div class="form-field clearfix">
				<label for="student">Student</label>
				<input type="checkbox" id="student" rel="customer_type" name="student" value="1"<? if(disp($_POST['student'], $user['student'])): ?>checked="checked"<? endif; ?> /><br />
			</div>
			<div class="form-field clearfix">
				<label for="confirmed_student">Confirmed Student</label>
				<input type="checkbox" id="confirmed_student" rel="customer_type" name="confirmed_student" value="1"<? if(disp($_POST['confirmed_student'], $user['confirmed_student'])): ?>checked="checked"<? endif; ?> /><br />
			</div>
			<div class="form-field clearfix">
				<label for="teacher">Teacher</label>
				<input type="checkbox" id="teacher" rel="customer_type" name="teacher" value="1"<? if(disp($_POST['teacher'], $user['teacher'])): ?>checked="checked"<? endif; ?> /><br />
			</div>
			<div class="form-field clearfix">
				<label for="shop">Shop</label>
				<input type="checkbox" id="shop" rel="customer_type" name="shop" value="1"<? if(disp($_POST['shop'], $user['shop'])): ?>checked="checked"<? endif; ?> /><br />
			</div>
			
			
			
			<?php if ( $config['psp']['driver'] == 'Authorize' ): ?>
			<div class="form-field clearfix">
				<label for="authorize_profile_id">Authorize.net Profile Id</label>
				<input type="text" id="authorize_profile_id" name="authorize_profile_id" value="<?php print $user['authorize_profile_id']?>" disabled="disabled" /><br />
			</div>
			<?php endif; ?>
		</div>
		<?php if($user['teacher']+0): ?>
		<!-- Teacher info -->
		<div id="tabs-5">
			<div class="form-field clearfix">
			    <label for="teacher_name">Name</label>
			    <input type="text" id="teacher_name" name="teacherinfo[name]" value="<?php print disp3($_POST['teacherinfo']['name'],$teacher['name'],$user['firstname']." ".$user['lastname'])?>" /><br />
			</div>
			<div class="form-field clearfix">
			    <label for="teacher_zip">Zip code</label>
			    <input type="text" id="teacher_zip" name="teacherinfo[zip]" value="<?php print disp($_POST['teacherinfo']['zip'],$teacher['zip'])?>" /><br />
			    
			</div>
			<div class="form-field clearfix">
			    <label for="teacher_phone">Phone</label>
			    <input type="text" id="teacher_phone" name="teacherinfo[phone]" value="<?php print disp($_POST['teacherinfo']['phone'],$teacher['phone'],$user['phone'])?>" /><br />
			</div>
			<div class="form-field clearfix">
			    <label for="teacher_website">Website</label>
			    <input type="text" id="teacher_website" name="teacherinfo[website]" value="<?php print disp($_POST['teacherinfo']['website'],$teacher['website']) ?>" /><br />
			</div>
			<div class="form-field clearfix">
			    <label for="teacher_email">Email</label>
			    <input type="email" id="teacher_email" name="teacherinfo[email]" value="<?php print disp3($_POST['teacherinfo']['email'],$teacher['email'],$user['email']) ?>" />
			</div>
		</div>
		
		<?php endif; #end if is teacher ?>
		
		<?php if($user['shop']+0): ?>
		<div id="tabs-6">
		    <div class="form-field clearfix">
			<label for="shop_name">Name</label>
			<input type="text" id="shop_name" name="shopinfo[name]" value="<?php print disp($_POST['shopinfo']['name'],$shop['name']) ?>" /><br />
		    </div>
		    <div class="form-field clearfix">
			<label for="shop_address1">Address1</label>
			<input type="text" id="shop_address1" name="shopinfo[address1]" value="<?php print disp($_POST['shopinfo']['address1'],$shop['address1']) ?>" /><br />
		    </div>
		    <div class="form-field clearfix">
			<label for="shop_address2">Address2</label>
			<input type="text" id="shop_address2" name="shopinfo[address2]" value="<?php print disp($_POST['shop']['address2'],$shop['address2']) ?>" /><br />
		    </div>
		    <div class="form-field clearfix">
			<label for="shop_city">City</label>
			<input type="text" id="shop_city" name="shopinfo[city]" value="<?php print disp($_POST['shopinfo']['city'],$shop['city']) ?>" /><br />
		    </div>
		    <div class="form-field clearfix">
			<label for="shop_zip">Zip</label>
			<input type="text" id="shop_zip" name="shopinfo[zip]" value="<?php print disp($_POST['shopinfo']['zip'],$shop['zip']) ?>" /><br />
		    </div>
		    <div class="form-field clearfix">
			<label for="shop_phone">Phone</label>
			<input type="text" id="shop_phone" name="shopinfo[phone]" value="<?php print disp($_POST['shopinfo']['phone'],$shop['phone']) ?>" /><br />
		    </div>
		    <div class="form-field clearfix">
			<label for="shop_website">Website</label>
			<input type="text" id="shop_website" name="shopinfo[website]" value="<?php print disp($_POST['shopinfo']['website'],$shop['website']) ?>" /><br />
		    </div>
		    <div class="form-field clearfix">
			<label for="shop_email">Email</label>
			<input type="text" id="shop_email" name="shopinfo[email]" value="<?php print disp($_POST['shopinfo']['email'],$shop['email']) ?>" /><br />
		    </div>
		    <div class="form-field clearfix">
			<label for="shop_rating">Rating</label>
			<select id="shop_rating" name="shopinfo[rating]">
			<?
				for($i=1;$i<6;$i++)
					if(disp($_POST['shopinfo']['rating'],$shop['rating']) == $i)
						echo '<option value="'.$i.'" selected="selected">'.$i.'</option>';
					else
						echo '<option value="'.$i.'">'.$i.'</option>';
			?>
			</select>
		    </div>
		    
		</div>
		<?php endif; #end if is shop?>
		
		<div id="tabs-2" class="address-wrapper">
			
			<div class="right" style="margin-bottom:1em;">
				<span class="button button-small-add"><input type="button" class="add add_address" value="New Address" /></span>
			</div>
			
			<? 
				$i = 0;
				while($row=$billing->FetchRow()) { 
					++$i;
			?>
				<div class="address" style="border:1px solid #EFEBEF;padding:5px;margin-bottom:4em;clear:both;">
					<div class="form-field clearfix">
						<label for="billing_address_name[<?=$i?>]">Name</label>
						<input type="text" class="text" id="billing_address_name[<?=$i?>]" name="billing_address_name[<?=$row['id']?>]" value="<?=$row['name']; ?>" />
					</div>
					
					<div class="form-field clearfix">
						<label for="billing_address_email[<?=$i?>]">Email</label>
						<input type="text" class="text" id="billing_address_email[<?=$i?>]" name="billing_address_email[<?=$row['id']?>]" value="<?=$row['email']; ?>" />
					</div>
					
					<div class="form-field clearfix">
						<label for="billing_address_phone[<?=$i?>]">Telephone</label>
						<input type="text" class="text" id="billing_address_phone[<?=$i?>]" name="billing_address_phone[<?=$row['id']?>]" value="<?=$row['phone']; ?>" />
					</div>
					
					<div class="form-field clearfix">
						<label for="billing_address_line1[<?=$i?>]">Address Line 1</label>
						<textarea id="billing_address_line1[<?=$i?>]" name="billing_address_line1[<?=$row['id']?>]"><?=$row['line1']; ?></textarea>
					</div>
					
					<div class="form-field clearfix">
						<label for="billing_address_line2[<?=$i?>]">Address Line 2</label>
						<textarea id="billing_address_line2[<?=$i?>]" name="billing_address_line2[<?=$row['id']?>]"><?=$row['line2']; ?></textarea>
					</div>
					
					<div class="form-field clearfix">
						<label for="billing_address_postcode[<?=$i?>]">Postcode</label>
						<input type="text" class="text" id="billing_address_postcode[<?=$i?>]" name="billing_address_postcode[<?=$row['id']?>]" value="<?=$row['postcode']; ?>" />
					</div>
					
					<div class="form-field clearfix" style="border-bottom:none;">
						<label for="billing_address_country[<?=$i?>]">Country</label>
						<?
						
							echo "<select id=\"billing_address_country[{$i}]\" name=\"billing_address_country_id[{$row['id']}]\">";
								foreach($countries as $country)
									if($country['id'] == $row['country_id'])
										echo '<option value="'.$country['id'].'" selected="selected">'.$country['name'].'</option>';
									else
										echo '<option value="'.$country['id'].'">'.$country['name'].'</option>';
							echo "</select>";
						
						?>
					</div>
					
					<div class="clearfix" style="margin:1em 0;">
						<a href="#" class="button button-grey remove-address right"><span>Remove</span></a>
					</div>
				</div>
				
			<? } ?>
			
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
			
			<? 
				$i = 0;
				while($row=$shipping->FetchRow()) { 
					++$i;
			?>
				<div class="address" style="border:1px solid #EFEBEF;padding:5px;margin-bottom:4em;clear:both;">
					<div class="form-field clearfix">
						<label for="delivery_name[<?=$i?>]">Name</label>
						<input type="text" class="text" id="delivery_name[<?=$i?>]" name="delivery_name[<?=$row['id']?>]" value="<?=$row['name']; ?>" />
					</div>
					
					<div class="form-field clearfix">
						<label for="delivery_email[<?=$i?>]">Email</label>
						<input type="text" class="text" id="delivery_email[<?=$i?>]" name="delivery_email[<?=$row['id']?>]" value="<?=$row['email']; ?>" />
					</div>
					
					<div class="form-field clearfix">
						<label for="delivery_phone[<?=$i?>]">Telephone</label>
						<input type="text" class="text" id="delivery_phone[<?=$i?>]" name="delivery_phone[<?=$row['id']?>]" value="<?=$row['phone']; ?>" />
					</div>
					
					<div class="form-field clearfix">
						<label for="delivery_line1[<?=$i?>]">Address Line 1</label>
						<textarea id="delivery_line1[<?=$i?>]" name="delivery_line1[<?=$row['id']?>]"><?=$row['line1']; ?></textarea>
					</div>
					
					<div class="form-field clearfix">
						<label for="delivery_line2[<?=$i?>]">Address Line 2</label>
						<textarea id="delivery_line2[<?=$i?>]" name="delivery_line2[<?=$row['id']?>]"><?=$row['line2']; ?></textarea>
					</div>
					
					<div class="form-field clearfix">
						<label for="delivery_postcode[<?=$i?>]">Postcode</label>
						<input type="text" class="text" id="delivery_postcode[<?=$i?>]" name="delivery_postcode[<?=$row['id']?>]" value="<?=$row['postcode']; ?>" />
					</div>
					
					<div class="form-field clearfix" style="border-bottom:none;">
						<label for="delivery_country[<?=$i?>]">Country</label>
						<?
						
							echo "<select id=\"delivery_country[{$i}]\" name=\"delivery_country_id[{$row['id']}]\">";
								foreach($countries as $country)
									if($country['id'] == $row['country_id'])
										echo '<option value="'.$country['id'].'" selected="selected">'.$country['name'].'</option>';
									else
										echo '<option value="'.$country['id'].'">'.$country['name'].'</option>';
							echo "</select>";
						
						?>
					</div>
					
					<div class="clearfix" style="margin:1em 0;">
						<a href="#" class="button button-grey remove-address right"><span>Remove</span></a>
					</div>
				</div>
				
			<? } ?>
			
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
		<div id="tabs-4">
			<div class="form-field clearfix">
				<table class="values nocheck" id="">
					<tr>
						<th>Code</th>
						<th>Expiry Date</th>
						<th>Use Count</th>
						<th>Used</th>
						<th>Remaining</th>
					</tr>
				<?
					while($row=$discount_codes->FetchRow())
					{
						if($class=="light")
							$class="dark";
						else
							$class="light";

						if($expiry_date = strtotime($row['expiry_date']))
							$expiry_date = date('d/m/Y', $expiry_date);
						else
							$expiry_date = '-';
							
						echo "<tr class=\"$class\">
							<td>{$row['code']}</td>
							<td>{$expiry_date}</td>
							<td>{$row['use_count']}</td>
							<td>{$row['used']}</td>
							<td>{$row['assigned']}</td>
							</tr>";
					}
				?>
				</table>
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
		<input type="hidden" name="user_id" value="<?=$user['id'] ?>" />
	</div>

</form>