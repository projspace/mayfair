<script type="text/javascript" src="<?=$config['dir'] ?>VLib/js/validator.js"></script>
<script language="javascript" type="text/javascript">
/* <![CDATA[ */
     
	var validations = {};
	validations['validateLoginDetails'] = function(){
		$('#frmLoginDetails input, #frmLoginDetails textarea').css('border', 'none');
		
		var validation = new Validator(function(errors){
			for(i=0;i<errors.length;i++)
			{
				$(errors[i].dom).css('border', '1px solid #B3351E');
			}
		});

		validation.addField('email', "Email", 'required|email');
		validation.addField('password', "Password", 'required|password');
		validation.addField('repeat_password', "Re-type password", 'required|password');
		
		if(!validation.validate())
			return false;
			
		if($.trim($('#password')) != $.trim($('#password')))
		{
			$('#password, #repeat_password').css('border', '1px solid #B3351E');
			return false;
		}
			
		return true;
	}
	
	var removeAddress = function(){
		var $this = this;
		$.ajax({
			url: '<?=$config['dir'] ?>ajax/act_RemoveAddress.php',
			type: 'post',
			data: 'address_id='+$($this).attr('address_id'),
			dataType: 'json',
			success: function(json){
				if(json.status)
					$($this).parents('tr').remove();
				else
					alert(json.message);
			}
		});
		return false;
	}
	
	$(document).ready(function(){
		$('#btn_wishlist').click();
		/*$('#add_address').click(function(){
			var $this = this;
			$.ajax({
				url: '<?=$config['dir'] ?>ajax/act_AddAddress.php',
				type: 'post',
				dataType: 'json',
				success: function(json){
					if(json.status)
					{
						var address_id = json.message;
						var html = '';
						
						var status = '';
						if($($this).parents('table').find('a.edit').text() == 'Edit')
							status = 'disabled="disabled"';
							
						html += '<tr>';
						html += '<td><a class="delete" href="#" address_id="'+address_id+'">&nbsp;</a><input type="text" class="editme" name="address['+address_id+']" value="" '+status+' /></td>';
						html += '<td>';
						html += '<input type="checkbox" name="billing['+address_id+']" value="1" '+status+' />';
						html += '</td>';
						html += '<td>';
						html += '<input type="checkbox" name="delivery['+address_id+']" value="1" '+status+' />';
						html += '</td>';
						html += '</tr>';
						$($this).parents('table').find('tbody').append(html);
						$('#addresses .delete').unbind('click').click(removeAddress);
					}
					else
						alert(json.message);
				}
			});
			return false;
		});*/
		
		$('#addresses .delete').click(removeAddress);
		
		$("#add_address").fancybox({ 
			'width'				: 375
			,'height'			: '25%'
			,'type'				: 'iframe'
			,'modal'			: false
			//,'autoDimensions'	: false
			, onComplete: function(){
				Cufon.replace('.overlay .header h1');
			}
			,onClosed: function(){
				window.location.reload(true);
			}
		});
		
		$(".edit_address").fancybox({ 
			'width'				: 375
			,'height'			: '25%'
			,'type'				: 'iframe'
			,'modal'			: false
			//,'autoDimensions'	: false
			, onComplete: function(){
				Cufon.replace('.overlay .header h1');
			}
			,onClosed: function(){
				window.location.reload(true);
			}
		});

		// Authorize requests
		$(".add_payments, .edit_payment_address").fancybox({
		    'width'				: 450
			,'height'			: 505
			,'type'				: 'iframe'
			,'modal'			: false
			,onClosed: function(){
				window.location.reload(true);
			}
		});

		$(".add_shipping, .edit_shipping_address").fancybox({
		    'width'				: 385
			,'height'			: 360
			,'type'				: 'iframe'
			,'modal'			: false
			,onClosed: function(){
				window.location.reload(true);
			}
		});
		

		$('a.delete.payment_address').click(function(e){
			e.preventDefault();

			if(!confirm('Are you sure?')) return false;
			var $this = this;
			$.ajax({
				url: "<?=$config['dir'] ?>index.php?fuseaction=user.deleteAuthorizePaymentProfile",
				type: 'post',
				data: {
						'authorize_profile_id' 	: <?php print $account['authorize_profile_id']; ?>,
						'payment_profile_id'	:	$($this).attr('address_id')
				},
				dataType: 'json',
				success: function(json){
					if(json.status)
						$($this).parents('tr').remove();
					else
						alert(json.message);
				}
			});
			
			return false;
		});

		$('a.delete.shipping_address').click(function(e){
			e.preventDefault();

			if(!confirm('Are you sure?')) return false;
			var $this = this;
			$.ajax({
				url: "<?=$config['dir'] ?>index.php?fuseaction=user.deleteAuthorizeShippingProfile",
				type: 'post',
				data: {
						'authorize_profile_id' 	: <?php print $account['authorize_profile_id']; ?>,
						'shipping_profile_id'	:	$($this).attr('address_id')
				},
				dataType: 'json',
				success: function(json){
					if(json.status)
						$($this).parents('tr').remove();
					else
						alert(json.message);
				}
			});
			
			return false;
		});
		

		$('input.default_payment_id').click(function(e){
		    var $this = $(this);
		    var data = {
				'authorize_profile_id' 	: <?php print $account['authorize_profile_id']; ?>,
				'payment_profile_id'	:	$this.val()
			};
			$.post(	"<?=$config['dir'] ?>index.php?fuseaction=user.setDefaultAuthorizePaymentProfile",
					data,
					function(json){},
					"json"
				);
		});

		$('input.default_shipping_id').click(function(e){
			    var $this = $(this);
			    var data = {
					'authorize_profile_id' 	: <?php print $account['authorize_profile_id']; ?>,
					'shipping_profile_id'	:	$this.val()
				};
				$.post(	"<?=$config['dir'] ?>index.php?fuseaction=user.setDefaultAuthorizeShippingProfile",
						data,
						function(json){},
						"json"
					);
		});

		
		
	});
/* ]]> */
</script>
<div id="content-wrapper">
	<article id="account">
		<header class="content-box"><h1>Your account</h1></header>
		<section class="content-box">
			<form action="<?=$config['dir'] ?>ajax/act_LoginDetails.php" method="post" validation="validateLoginDetails" id="frmLoginDetails">
				<table class="data-table">
					<thead>
						<tr class="manyButtons">
							<th><a href="<?=$config['dir'] ?>logout" class="btn-gray" style="display: block;">Logout</a></th>
							<th align="center"></th>
							<th>
								<p class="submit-buttons clear-top" style='padding-left: 300px;'>
									<a href="<?=$config['dir'] ?>" class="btn-gray">Continue Shopping</a>
									<a href="<?=$config['dir'] ?>account/gift-registry" class="btn-gray">Gift Registry</a>
								</p>
							</th>
						</tr>
						<tr>
							<th>Login Details</th>
							<th colspan="2"></th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<td colspan="3">
								<a href="#" class="btn-gray-small edit">Edit</a>
							</td>
						</tr>
					</tfoot>
					<tbody>
						<tr>
							<td>Email</td>
							<td><input type="email" class="editme" id="email" name="email" value="<?=$account['email'] ?>" disabled="disabled" /></td>
						</tr>
						<tr>
							<td>Password</td>
							<td><input type="password" class="editme" id="password" name="password" value="<?=$account['password'] ?>" disabled="disabled" /></td>
						</tr>
						<tr>
							<td>Re-type Password</td>
							<td><input type="password" class="editme" id="repeat_password" name="repeat_password" value="<?=$account['password'] ?>" disabled="disabled" /></td>
						</tr>
					</tbody>
				</table>
			</form>
			<form action="<?=$config['dir'] ?>ajax/act_UserDetails.php" method="post">
				<table class="data-table">
					<thead>
						<tr>
							<th>Details</th>
							<th></th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<td colspan="2">
								<a href="#" class="btn-gray-small edit">Edit</a>
							</td>
						</tr>
					</tfoot>
					<tbody>
						<tr>
							<td>First name</td>
							<td><input type="text" class="editme" name="firstname" value="<?=$account['firstname'] ?>" disabled="disabled" /></td>
						</tr>
						<tr>
							<td>Last name</td>
							<td><input type="text" class="editme" name="lastname" value="<?=$account['lastname'] ?>" disabled="disabled" /></td>
						</tr>
						<tr>
							<td>Date of birth</td>
							<td><input type="email" class="editme" name="dob" value="<?=($dob = strtotime($account['dob']))?date('d/m/Y', $dob):'-' ?>" disabled="disabled" /></td>
						</tr>
						<tr>
							<td>Phone</td>
							<td><input type="text" class="editme" name="phone" value="<?=$account['phone'] ?>" disabled="disabled" /></td>
						</tr>
						<!--<tr>
							<td>Please tell us about yourself</td>
							<td><textarea class="editme" name="info" id="info" rows="7" cols="20" disabled="disabled"><?=$account['info'] ?></textarea></td>
						</tr>-->
					</tbody>
				</table>
			</form>
			
			<? if($additional_payment): ?>
			<table class="data-table">
				<thead>
					<tr>
						<th>Card Details</th>
						<th></th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<td colspan="2">
							<a href="<?=$config['dir'] ?>account/payment-remove" class="btn-gray-small">Remove</a>
						</td>
					</tr>
				</tfoot>
				<tbody>
					<tr>
						<td>Saved card nickname</td>
						<td><input type="text" class="editme" value="<?=$additional_payment['label'] ?>" readonly="readonly" /></td>
					</tr>
				</tbody>
			</table>
			<? endif; ?>
			<?php /* ?>
			<form action="<?=$config['dir'] ?>ajax/act_UserAddresses.php" method="post">
				<table class="data-table" id="addresses">
					<thead>
						<tr>
							<th>Address Book</th>
							<th>Billing</th>
							<th>Delivery</th>
						</tr>
					</thead>
					<tbody class="borders">
					<?
						while($row = $addresses->FetchRow())
						{
							$display = array();
							if(trim($row['name']) != '')
								$display[] = trim($row['name']);
							if(trim($row['email']) != '')
								$display[] = trim($row['email']);
							if(trim($row['phone']) != '')
								$display[] = trim($row['phone']);
							if(trim($row['line1']) != '')
								$display[] = trim($row['line1']);
							if(trim($row['line2']) != '')
								$display[] = trim($row['line2']);
							if(trim($row['line3']) != '')
								$display[] = trim($row['line3']);
							if(trim($row['line4']) != '')
								$display[] = trim($row['line4']);
							if(trim($row['postcode']) != '')
								$display[] = trim($row['postcode']);
							if(trim($row['country']) != '')
								$display[] = trim($row['country']);
								
							echo '
								<tr>
									<td>
										<a class="delete" href="#" address_id="'.$row['id'].'">&nbsp;</a>
										<a style="float: left; width: 700px;" class="edit_address" href="'.$config['dir'].'account/editAddress/'.$row['id'].'?ajax=1">'.implode(', ', $display).'</a>
									</td>
									<td style="width: 50px;">
										<input type="checkbox" name="billing['.$row['id'].']" value="1" disabled="disabled" '.($row['billing']?'checked="checked"':'').' />
									</td>
									<td style="width: 50px;">
										<input type="checkbox" name="delivery['.$row['id'].']" value="1" disabled="disabled" '.($row['delivery']?'checked="checked"':'').' />
									</td>
								</tr>';
						}
					?>
					</tbody>
					<tfoot>
						<tr>
							<td colspan="3">
								<a class="btn-gray-small" href="<?=$config['dir'] ?>account/addAddress?ajax=1" id="add_address">Add address</a>
							</td>
						</tr>
					</tfoot>
				</table>
			</form>
			<?php */ ?>	
			<!-- Payment Details & Address -->	
			<table class="data-table" id="payments">
				<thead>
				<tr>
					<th>Details</th>
					<?php /*?>
					<th>Default</th>
					<?php */ ?>
				</tr>
				</thead>
				<tfoot>
				<tr>
					<td colspan="4">
						<a href="<?php print $config['dir']?>index.php?fuseaction=user.addAuthorizePaymentProfile&ajax=1>" class="btn-gray  add_payments">&nbsp;&nbsp;&nbsp;Add Payment Details&nbsp;&nbsp;&nbsp;</a>
						<!-- Add payment -->
					</td>
				</tr>
				</tfoot>
				<?php
				if ( count($authorize_profile_xml->xml->profile->paymentProfiles) ):
				?>
				
				<tbody class="borders">
					<?php foreach($authorize_profile_xml->xml->profile->paymentProfiles as $profile):
							$display = array();
							
							if(trim($profile->billTo->firstName))
								$display[] = $profile->billTo->firstName;
							if(trim($profile->billTo->lastName))
								$display[] = $profile->billTo->lastName;
							if(trim($profile->billTo->company))
								$display[] = $profile->billTo->company;
							if(trim($profile->billTo->address))
								$display[] = $profile->billTo->address;
							if(trim($profile->billTo->city))
								$display[] = $profile->billTo->city;
							if(trim($profile->billTo->state))
								$display[] = $profile->billTo->state;
							if(trim($profile->billTo->zip))
								$display[] = $profile->billTo->zip;
							if(trim($profile->billTo->country))
								$display[] = $profile->billTo->country;
							if(trim($profile->billTo->phoneNumber))
								$display[] = $profile->billTo->phoneNumber;
							if(trim($profile->billTo->faxNumber))
								$display[] = $profile->billTo->faxNumber;
							if(trim($profile->payment->creditCard->cardNumber))
								$display[] = $profile->payment->creditCard->cardNumber;
									
					
					?>
					<tr>
						<td>
							<a class="delete payment_address" href="#" address_id="<?php print $profile->customerPaymentProfileId; ?>">&nbsp;</a>
							<a style="float: left; width: 700px;" class="edit_payment_address" href="<?php print $config['dir'].'index.php?fuseaction=user.editAuthorizePaymentProfile&payment_profile_id='.$profile->customerPaymentProfileId?>&ajax=1"><?php print implode(', ', $display)?></a>
						</td>
						<?php /*?>
						<td>
							<input type="radio" name="def" class="default_payment_id" value='<?php print $profile->customerPaymentProfileId; ?>' 
								<?php if($account['authorize_default_payment_id'] == $profile->customerPaymentProfileId ): ?> checked="checked" <?php endif; ?> 
							/>
						</td>
						<?php */?>
					</tr>
					<?php endforeach;?>
				</tbody>
				<?php endif;?>
			</table>
				
				
			<!--  Shipping Address -->	
			<table class="data-table" id="shipping">
				<thead>
				<tr>
					<th>Details</th>
					<?php /* ?><th>Default</th><?php */?>
				</tr>
				</thead>
				<tfoot>
				<tr>
					<td colspan="2">
						<a href="<?php print $config['dir']?>index.php?fuseaction=user.addAuthorizeShipingProfile&ajax=1>" class="btn-gray add_shipping">&nbsp;&nbsp;&nbsp;Add Shipping Details&nbsp;&nbsp;&nbsp;</a>
						<!-- Add payment -->
					</td>
				</tr>
				</tfoot>
				<?php
				if ( count($authorize_profile_xml->xml->profile->shipToList) ):
				?>
				
				<tbody class="borders">
					<?php foreach($authorize_profile_xml->xml->profile->shipToList as $profile):
							
							$display = array();
							
							if(trim($profile->firstName))
								$display[] = $profile->firstName;
							if(trim($profile->lastName))
								$display[] = $profile->lastName;
							if(trim($profile->company))
								$display[] = $profile->company;
							if(trim($profile->address))
								$display[] = $profile->address;
							if(trim($profile->city))
								$display[] = $profile->city;
							if(trim($profile->state))
								$display[] = $profile->state;
							if(trim($profile->zip))
								$display[] = $profile->zip;
							if(trim($profile->country))
								$display[] = $profile->country;
							if(trim($profile->phoneNumber))
								$display[] = $profile->phoneNumber;
							if(trim($profile->faxNumber))
								$display[] = $profile->faxNumber;
					
					?>
					<tr>
						<td>
							<a class="delete shipping_address" href="#" address_id="<?php print $profile->customerAddressId; ?>">&nbsp;</a>
							<a style="float: left; width: 700px;" class="edit_shipping_address" href="<?php print $config['dir'].'index.php?fuseaction=user.editAuthorizeShippingProfile&shipping_profile_id='.$profile->customerAddressId?>&ajax=1"><?php print implode(', ', $display)?></a>
						</td>
						<?php /*?>
						<td>
							<input type="radio" name="shiping_default" class="default_shipping_id" value='<?php print $profile->customerAddressId; ?>' 
								<?php if($account['authorize_default_shipping_id'] == $profile->customerAddressId ): ?> checked="checked" <?php endif; ?> 
							/>
						</td>
						<?php */?>
					</tr>
					<?php endforeach;?>
				</tbody>
				<?php endif;?>
			</table>	
			
			
			
			<table class="data-table">
				<thead>
					<tr>
						<th>Recent orders</th>
						<th>Order No.</th>
						<th>Order Date</th>
						<th>Status</th>
						<th>Date Shipped</th>
						<th>Details</th>
					</tr>
				</thead>
				<tbody>
				<?
					while($row = $orders->FetchRow())
					{
						if($row['dispatched'])
							$status = 'Shipped';
						elseif($row['processed'])
							$status = 'Processed';
						elseif($row['id'])
							$status = 'In Progress';
						else
							$status = 'Pending';
							
						echo '
							<tr>
								<td></td>
								<td>'.$row['id'].'</td>
								<td>'.(($time = $row['time']+0)?date('d/m/Y', $time):'--/--/----').'</td>
								<td>'.$status.'</td>
								<td>'.(($time = $row['dispatched']+0)?date('d/m/Y', $time):'--/--/----').'</td>
								<td><a href="'.$config['dir'].'admins/invoice.php?order_id='.$row['id'].'" target="_blank">View Order</a></td>
							</tr>';
					}
				?>
				</tbody>
			</table>
			<p class="submit-buttons">
				<a href="<?=$config['dir'] ?>" class="btn-gray">Continue Shopping</a>
			</p>
			<? if($wishlist->RecordCount()): ?>
			<section class="accordion">
				<h1><a href="#" id="btn_wishlist">Your wishlist</a></h1>
				<ul class="cart-items" id='wishlist'>
				<?
					while($row = $wishlist->FetchRow())
					{
						if($row['image_type'])
							$image = $config['dir'].'images/product/medium/'.$row['image_id'].'.'.$row['image_type'];
						else
							$image = $config['dir'].'images/product/medium/placeholder.jpg';
							
						$options = array();
						$options[] = 'Qty '.$row['quantity'];
						if(trim($row['size']) != '')
							$options[] = 'Size / '.$row['size'];
						if(trim($row['width']) != '')
							$options[] = 'Width / '.$row['width'];
						if(trim($row['color']) != '')
							$options[] = 'Colour / '.$row['color'];
						
						$name = array();
						if(trim($row['code']) != '')
							$name[] = $row['code'];
						$name[] = $row['name'];
						
						echo '
							<li class="yui3-g">
								<div class="box product yui3-u" style="width: 411px;">
									<figure>
										<div class="vertical-img h90"><span class="middle-img" style="background-color: #FFFFFF;"><img src="'.$image.'" width="90" alt=""  /></span></div>
									</figure>
									<h2>'.implode(' ', $name).'</h2>
									'.$row['short_description'].'
								</div>
								<div class="box attributes yui3-u">
									<p>'.implode('</p><p>', $options).'</p>
								</div>
								<div class="box price yui3-u">'.price($row['price']).'</div>
								<p class="buttons yui3-u-1">
									<a href="'.$config['dir'].'wishlist/cart/'.$row['wish_id'].'" class="btn-gray-small">Add to cart</a>
									<a href="'.$config['dir'].'wishlist/remove/'.$row['wish_id'].'?return_url='.urlencode($config['dir'].'account').'" class="btn-gray-small remove">Remove</a>
								</p>
							</li>';
					}
				?>
				</ul>
			</section>
			<? endif; ?>
		</section>
	</article>
	
	
</div>
