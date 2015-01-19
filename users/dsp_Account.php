<?
	$page = $elems->qry_Page(68);
?>
<style type="text/css">
    .tab-nav li { width: 117px; padding: 10px 8px; background-position: bottom right; }
    .tab-nav li.active { background-position: top right; }
    /*.tab-content > div { display: none; }*/
    input.editable { color: #000; }
    .iframe-container iframe { border: medium none; display: block; height: 100%; width: 100%; }
    .tab-content { display: none; }
</style>
<div class="banner banner-small">
    <div class="banner-info"><img src="<?=$config['layout_dir'] ?>images/banner3.jpg" alt="banner" />
        <div class="banner-content">
            <?=$page['content'] ?>
        </div>
    </div>
</div>
<div class="block">
    <div class="tab-wrapper">
        <ul class="tab-nav">
            <li class="active"><a href="#" >Login</a></li>
            <li><a href="#" >My details</a></li>
            <!--<li><a href="#" >Payment</a></li>-->
            <li><a href="#" >Shipping</a></li>
            <li><a href="#" >My Orders</a></li>
            <li><a href="#" id="wishlist">Wishlist</a></li>
            <li><a href="#" >Gift Registry</a></li>
        </ul>
        <!-- Login -->
        <div class="tab-content" style="display: block;">
            <form action="<?=$config['dir'] ?>ajax/act_LoginDetails.php" method="post" validation="validateLoginDetails" id="frmLoginDetails">
                <div class=" detail-section block-full">
                    <div class="block">
                        <label for="email">Email:</label>
                        <input type="text" class="input-box omega clearable" value="<?=$account['email'] ?>" name="email" id="email" autocomplete="off" placeholder="required" disabled="disabled" />
                        <div class="clear"></div>
                    </div>
                    <div class="block">
                        <label for="password">Password:</span></label>
                        <input type="password" class="input-box omega" value="" name="password" id="password" autocomplete="off" disabled="disabled" />
                        <div class="clear"></div>
                    </div>
                    <div class="block">
                        <label for="repeat_password">Re-type password:</label>
                        <input type="password" class="input-box omega" value="" name="repeat_password" id="repeat_password" disabled="disabled" />
                        <div class="clear"></div>
                    </div>
                    <div class="block">
                        <a href="#" class="btn big-btn green-btn top-space edit">Edit</a>
                        <div class="clear"></div>
                    </div>
                </div>
                <div class="clear"></div>
            </form>
        </div>
        <!-- My details -->
        <div class="tab-content">
            <form action="<?=$config['dir'] ?>ajax/act_UserDetails.php" method="post">
                <div class=" detail-section block-full">
                    <div class="block">
                        <label>First name:</label>
                        <input type="text" class="input-box omega" value="<?=$account['firstname'] ?>" name="firstname" disabled="disabled" />
                        <div class="clear"></div>
                    </div>
                    <div class="block">
                        <label>Last name:</label>
                        <input type="text" class="input-box omega" value="<?=$account['lastname'] ?>" name="lastname" disabled="disabled" />
                        <div class="clear"></div>
                    </div>
                    <div class="block">
                        <label>Date of birth:</label>
                        <input type="text" class="input-box omega" value="<?=($dob = strtotime($account['dob']))?date('d/m/Y', $dob):'-' ?>" name="dob" disabled="disabled" />
                        <div class="clear"></div>
                    </div>
                    <div class="block">
                        <label>Phone:</label>
                        <input type="text" class="input-box omega" value="<?=$account['primary_phone'] ?>" name="primary_phone" disabled="disabled" />
                        <div class="clear"></div>
                    </div>
                    <div class="block">
                        <a href="#" class="btn big-btn green-btn top-space edit">Edit</a>
                        <div class="clear"></div>
                    </div>
                </div>
                <div class="clear"></div>
            </form>
        </div>
        <!-- Payment --
        <div class="tab-content">
            <div class=" detail-section block-full">
                <table class="product-detail" style="width: 100%;">
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
                    <tr class="totalprice">
                        <td style="width: 20px;"><a class="delete payment_address" href="#" address_id="<?php print $profile->customerPaymentProfileId; ?>">&nbsp;</a></td>
                        <td><a class="edit_payment_address" href="<?php print $config['dir'].'index.php?fuseaction=user.editAuthorizePaymentProfile&payment_profile_id='.$profile->customerPaymentProfileId?>&ajax=1"><?php print implode(', ', $display)?></a></td>
                    </tr>
                    <?php endforeach;?>
                </table>
                <div class="block">
                    <a href="<?php print $config['dir']?>index.php?fuseaction=user.addAuthorizePaymentProfile&ajax=1>" class="btn big-btn green-btn top-space add_payments">Add Payment Details</a>
                    <div class="clear"></div>
                </div>
            </div>
        </div>-->
        <!-- Shipping -->
        <div class="tab-content">
            <div class=" detail-section block-full">
                <table class="product-detail" style="width: 100%;">
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
                    <tr class="totalprice">
                        <td style="width: 20px;"><a class="delete shipping_address" href="#" address_id="<?php print $profile->customerAddressId; ?>">&nbsp;</a></td>
                        <td><a class="edit_shipping_address" href="<?php print $config['dir'].'index.php?fuseaction=user.editAuthorizeShippingProfile&shipping_profile_id='.$profile->customerAddressId?>&ajax=1"><?php print implode(', ', $display)?></a></td>
                    </tr>
                    <?php endforeach;?>
                </table>
                <div class="block">
                    <a href="<?php print $config['dir']?>index.php?fuseaction=user.addAuthorizeShipingProfile&ajax=1>" class="btn big-btn green-btn top-space add_shipping">Add Shipping Details</a>
                    <div class="clear"></div>
                </div>
            </div>
        </div>
        <!-- My Orders -->
        <div class="tab-content">
            <div class=" detail-section block-full">
                <table class="product-detail" style="width: 100%;">
                    <tr>
                        <th>Order No.</th>
                        <th>Order Date</th>
                        <th>Status</th>
                        <th>Date Shipped</th>
                        <th>Details</th>
                    </tr>
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
                                    <td>'.$row['id'].'</td>
                                    <td>'.(($time = $row['time']+0)?date('d/m/Y', $time):'--/--/----').'</td>
                                    <td>'.$status.'</td>
                                    <td>'.(($time = $row['dispatched']+0)?date('d/m/Y', $time):'--/--/----').'</td>
                                    <td><a href="'.$config['dir'].'admins/invoice.php?order_id='.$row['id'].'" target="_blank">View Order</a></td>
                                </tr>';
                        }
                    ?>
                </table>
                <div class="block">
                    <a href="<?php print $config['dir']?>" class="btn big-btn green-btn top-space">Continue Shopping</a>
                    <div class="clear"></div>
                </div>
            </div>
        </div>
        <!-- Wishlist -->
        <div class="tab-content">
            <div id="product-detail">
                <table class="product-detail" id="wishlist2">
                <?
                    while($row = $wishlist->FetchRow())
                    {
                        if($row['image_type'])
                            $image = $config['dir'].'images/product/medium/'.$row['image_id'].'.'.$row['image_type'];
                        else
                            $image = $config['dir'].'images/product/medium/placeholder.jpg';

                        $options = array();
                        $options[] = 'Qty '.$row['cart_quantity'];
                        if(trim($row['size']) != '')
                            $options[] = 'Size / '.$row['size'];
                        if(trim($row['width']) != '')
                            $options[] = 'Option / '.$row['width'];
                        if(trim($row['color']) != '')
                            $options[] = 'Color / '.$row['color'];

                        $name = array($row['name']);
                        if(trim($row['code']) != '')
                            $name[] = $row['code'];

                        $price = price($row['price']);

                        echo '
                            <tr>
                                <td class="first">
                                    <a href="'.product_url($row['id'], $row['guid']).'"><span class="thumb-frame"><img src="'.$image.'" alt="product" width="124" height="124" /></span></a>
                                    <p>'.implode('<br />', $name).'</p>
                                </td>
                                <td>'.implode('</p><p>', $options).'</td>
                                <td>
                                    <a href="'.$config['dir'].'wishlist/cart/'.$row['wish_id'].'" class="link">Add to cart</a>
                                    <a href="'.$config['dir'].'wishlist/remove/'.$row['wish_id'].'?return_url='.urlencode($config['dir'].'account#wishlist').'" class="link">Remove</a>
                                </td>
                                <td>'.$price.'</td>
                            </tr>';
                    }
                ?>
                </table>
            </div>
        </div>
        <!-- Gift Registry -->
        <div class="tab-content iframe">
            <div class="iframe-container" id="account-gift-registry">
                <iframe scrolling="auto" frameborder="0" src="<?= $config['dir'] ?>account/gift-registry" hspace="0"></iframe>
            </div>
        </div>
    </div>
</div>

<? $elems->placeholder('script')->captureStart() ?>
<script type="text/javascript" src="<?=$config['dir'] ?>VLib/js/validator.js"></script>
<script language="javascript" type="text/javascript">
/* <![CDATA[ */
	var validations = {};
	validations['validateLoginDetails'] = function(){
		$('#frmLoginDetails input, #frmLoginDetails textarea').removeClass('error').removeClass('valid');
		$('#frmLoginDetails label.error, #frmLoginDetails label.valid').remove();

        var fields = [];
        fields[fields.length] = {'id':'email', 'name':"Email", 'type':'required|email'};
        fields[fields.length] = {'id':'password', 'name':"Password", 'type':'required|password'};
        fields[fields.length] = {'id':'repeat_password', 'name':"Re-type password", 'type':'required|password'};

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

		return true;
	}
/* ]]> */
</script>
<script language="javascript" type="text/javascript">
/* <![CDATA[ */
	$(document).ready(function(){
        if(window.location.hash)
            $('ul.tab-nav li a'+window.location.hash).click();
        
        $('.tab-content a.edit').click(function(){
            var toDo = $(this).text();
            var form = $(this).parents('form');
            var getDisabledInputs = form.find('input,textarea');

            // make inputs editable
            if ( toDo == 'Edit' ) {
                getDisabledInputs.each(function(){
                    $(this).attr('disabled',false).addClass('editable');
                });
                if(getDisabledInputs.get(0) != undefined)
                    getDisabledInputs.get(0).focus();
                $(this).text('Save');
            } else {
                var validation = $.trim(form.attr('validation'));
                var pass = true;
                if(validation != '')
                    pass = validations[validation]();
                if(!pass)
                    return false;

                var ret = false;
                $.ajax({
                    async: false,
                    url: form.attr('action'),
                    type: 'post',
                    data: form.serialize(),
                    dataType: 'json',
                    success: function(json){
                        ret = json.status;
                        if(!json.status)
                            alert(json.message)
                    }
                });
                if(!ret)
                    return false;

                getDisabledInputs.each(function(){
                    $(this).attr('disabled',true).removeClass('editable');
                });
                $(this).text('Edit');
            }

            return false;
        });

        // Authorize requests
		$(".add_payments, .edit_payment_address").fancybox({
		    'width'				: 450
			,'height'			: 505
            ,'type'				: 'iframe'
            ,'modal'			: false
            ,'padding'			: 0
            ,'overlayColor'		: '#CCCCCC'
            , onStart: function(){
                $('#fancybox-close').css({top: '10px', right: '10px', width: '15px', height: '15px', background: '#CCCCCC url("'+config_dir+'layout/templates/mayfair/css/fancybox/close.gif") no-repeat scroll'});
            }
			,onClosed: function(){
				window.location.reload(true);
			}
		});

		$(".add_shipping, .edit_shipping_address").fancybox({
		    'width'				: 385
			,'height'			: 360
			,'type'				: 'iframe'
			,'modal'			: false
            ,'padding'			: 0
            ,'overlayColor'		: '#CCCCCC'
            , onStart: function(){
                $('#fancybox-close').css({top: '10px', right: '10px', width: '15px', height: '15px', background: '#CCCCCC url("'+config_dir+'layout/templates/mayfair/css/fancybox/close.gif") no-repeat scroll'});
            }
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
    });
/* ]]> */
</script>
<? $elems->placeholder('script')->captureEnd() ?>