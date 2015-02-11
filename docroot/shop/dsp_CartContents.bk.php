<script src="<?=$config['dir'] ?>VLib/js/validator.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">
/* <![CDATA[ */
	function validateDiscountCode()
	{
		$('#frmCart input:text').removeClass('error').next('label.error').hide();

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

		validation.addField('discount_code','Discount Code','required');
		
		return validation.validate();
	}
	
	function validatePickup()
	{
		$('#frmCart input:text').removeClass('error').next('label.error').hide();

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

		validation.addField('pick_up_date','Pickup Date','required');
		
		return validation.validate();
	}
	
	var apply_button = false;
	$(document).ready(function(){
		$('#frmCart').submit(function(){
			var value = $.trim($('#discount_code').val());
			if(value != '' && !apply_button && value != '<?=addcslashes($session->session->fields['discount_code'], "'") ?>')
				$('#return_url').val('<?=$config['dir'] ?>cart');
				
			<? if($vars['pick_up_only']): ?>
			return validatePickup();
			<? endif; ?>
		});
		
		$("#discount_submit").click(function(){
			if(validateDiscountCode())
			{
				apply_button = true;
				$('#return_url').val('<?=$config['dir'] ?>cart');
				$('#frmSubmit').click();
			}
			return false;
		});
		
		$('input.calendar').each(function(){
			$(this).datepicker({dateFormat: 'dd/mm/yy'});
		});

	});
/* ]]> */
</script>
<script language="javascript" type="text/javascript">
/* <![CDATA[ */
	function ajax_cart(cart_id, value, options)
	{
		function price($price)
		{
			return (($price < 0)?'-':'')+'&pound;'+Math.abs($price).toFixed(2);
		}
		
		var data = 'quantity['+cart_id+"]="+value;
		$('ul.selector input[name="option['+cart_id+'][]"]').each(function(){
			data += '&option['+cart_id+'][]='+$(this).val();
		});
		$.ajax({
				async: false
				,type: "GET"
				,dataType: "script"
				,url: "<?=$config['dir'] ?>ajax/act_ModifyCart.php"
				,data: data
			});
		if(typeof vars == 'object')
		{
			var params=['promotional_discount','discount','multibuy_discount','total','packing','shipping'];
			for(i=0;i<params.length;i++)
				vars[params[i]] = parseFloat(vars[params[i]]);
				
			$('input.qty[rel="'+cart_id+'"]').parents('td.quantity').siblings('td.price').html(price(parseFloat(product_price)));
			
			$('#container_promotional_discount').html(price(vars['promotional_discount']*-1));
			$('#container_discount').html(price(vars['discount']*-1));
			$('#container_multibuy_discount').html(price(vars['multibuy_discount']*-1));
			$('#container_total').html(price(vars['total']));
			$('#container_postage_packing').html(price(vars['packing']+vars['shipping']));
			$('#container_final_total').html(price(vars['total']+vars['packing']+vars['shipping']));
		}
	}
	$(document).ready(function(){
		$(".basket .plus, .basket .minus").click(function(){
			var quantity = parseInt($(this).siblings('input').val());
			if(isNaN(quantity))
				return false;
			if(quantity == 0)
				$(this).parents('.basket tr').remove();
			
			var cart_id = $(this).siblings('input').attr('rel');
			$('.parent_'+cart_id).text(quantity);
			ajax_cart(cart_id, quantity, true);
			return false;
		});
		
		$(".basket input.qty").keyup(function(){
			var quantity = parseInt($(this).val());
			if(isNaN(quantity))
				return false;
			if(quantity == 0)
				$(this).parents('.basket tr').remove();
			
			var cart_id = $(this).attr('rel');
			$('.parent_'+cart_id).text(quantity);
			ajax_cart(cart_id, quantity, true);
		});
		
		$(".basket ul.selector li > a").click(function(){
			$(this).siblings('dl').find('dd a').click(function(){
				$(this).parents('tr').find('input.qty').keyup();
			});
		});
	});
/* ]]> */
</script>
<article id="innerShop">
<?
	$content_area = $elems->qry_ContentArea(2);
	echo $content_area['description'];
?>
</article>
<article id="shopContent">
	<form id="frmCart" action="<?=$config['dir'] ?>cart?act=saveDetails" method="post">
		<input type="hidden" id="return_url" name="return_url" value="" />
		<table class="basket">
			<thead>
				<tr>
					<th colspan="2">Item</th>
					<th>Quantity</th>
					<th class="price">Price</th>
					<th class="remove">Remove</th>
				</tr>
			</thead>
			<tbody>
			<?
				foreach($rows as $index=>$row)
				{
					$class = '';
					if($index == 0)
						$class = 'class="firstRow"';
					if($index == count($rows)-1)
						$class = 'class="lastRow"';
						
					$options = '';
					if($row['options'])
					{
						if($row['parent_id'])
						{
							$options .= '<br />';
							foreach($row['options'] as $option_index=>$option)
								$options .= '<strong>'.$option['name'].'</strong>: '.$option['value'][$row['cart_options'][$option_index]].'<br />';
						}
						else
						{
							$options .= '<br /><ul class="selector">';
							foreach($row['options'] as $option_index=>$option)
							{
								$values = '';
								$selected = array('value'=>'', 'name'=>htmlentities($option['name'], ENT_NOQUOTES, 'UTF-8'));
								if(count($option['value']))
									foreach($option['value'] as $index=>$value)
									{
										$value = htmlentities($value, ENT_NOQUOTES, 'UTF-8');
										$price = $option['price'][$index]+0;
										if($price > 0)
										{
											if($row['vat'])
												$price = $price*(100+VAT)/100;
											$value .= ' (+'.price($price).')';
										}
											
										if($index == $row['cart_options'][$option_index])
											$selected = array('value'=>$index, 'name'=>$value);
										$values .= '<dd><a href="#" rel="'.$index.'">'.$value.'</a></dd>';
									}
								$options .= '<li style="margin: 0 0 5px 0; width: 158px;"><a class="arrowed" href="#" style="width: 152px;">'.$selected['name'].'</a><dl>';
								$options .= $values;
								$options .= '</dl><input type="hidden" name="option['.$row['cart_id'].'][]" value="'.$selected['value'].'" /></li>';
							}
							$options .= '</ul>';
						}
					}
					
					if($row['parent_id'])
						$quantity = ''.$row['cart_quantity'];
					else
						$quantity = '<a class="minus" href="#">-</a><input type="text" value="'.$row['cart_quantity'].'" name="quantity['.$row['cart_id'].']" rel="'.$row['cart_id'].'" class="qty" /><a class="plus" href="#">+</a>';
						
					if($row['imagetype'])
						$image = $config['dir'].'images/product/thumb/'.$row['id'].'.'.$row['imagetype'];
					else
						$image = $config['layout_dir'].'images/default-thumb.gif';
						
					echo '
						<tr '.$class.'>
							<td class="imagePreview"><a href="#"><img src="'.$image.'" width="71" height="69" alt="*"/></a></td>
							<td><a href="#"><em>'.htmlentities($row['name'], ENT_NOQUOTES, 'UTF-8').'</em>'.ucwords($row['tags']).'</a><br />'.$options.'</td>
							<td class="quantity"><span class="parent_'.$row['parent_id'].'">'.$quantity.'</span></td>
							<td class="price">'.price($row['cart_price']).'</td>
							<td class="remove">'.($row['parent_id']?'':'<a href="'.$config['dir'].'remove?cart_id='.$row['cart_id'].'">x</a>').'</td>
						</tr>';
				}
			?>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="2" class="discountCode"><em>discount code</em><span><input type="text" class="discount not-clearable" id="discount_code" name="discount_code" value="<?=$session->session->fields['discount_code'] ?>" /></span></td>
					<td><a href="#" class="redButton" style="padding: 3px 6px;" id="discount_submit">Apply</a></td>
					<td class="price" id="container_promotional_discount"><?=price($vars['promotional_discount']*(-1)) ?></td>
					<td></td>
				</tr>
				<? if($vars['pick_up_only']): ?>
				<tr>
					<td colspan="5" class="discountCode">
						<em>Pickup date</em>
						<span style="float: left;"><input type="text" class="calendar not-clearable" id="pick_up_date" name="pick_up[date]" value="<?=($time = strtotime($session->session->fields['pick_up_date']))?date('d/m/Y', $time):'' ?>" /></span>
						<ul class="selector row" style="float: left; margin-left: 5px;">
							<li style="width: 50px;">
								<?
									if($time)
										$hour = date('H', $time);
									else
										$hour = 0;
									$values = '';
									$selected = array('value'=>'0', 'name'=>'00');
									for($i=0;$i<24;$i++)
									{
										$value = sprintf("%02u", $i);
										if($i == $hour)
											$selected = array('value'=>$i, 'name'=>$value);
										$values .= '<dd><a href="#" rel="'.$i.'">'.$value.'</a></dd>';
									}
								?>
								<span><a style="width: 44px; border: 1px solid #8A8B8C" class="arrowed validate" href="#"><?=$selected['name'] ?></a></span>
								<dl>
									<?=$values ?>
								</dl>
								<input type="hidden" id="pick_up_hour" name="pick_up[hour]" value="<?=$selected['value'] ?>" />
							</li>
						</ul>
						<span style="float: left; margin-left: 5px; width: 2px;">:</span>
						<ul class="selector row" style="float: left; margin-left: 5px;">
							<li style="width: 50px;">
								<?
									if($time)
										$minute = date('i', $time);
									else
										$minute = 0;
									$values = '';
									$selected = array('value'=>'0', 'name'=>'00');
									for($i=0;$i<24;$i++)
									{
										$value = sprintf("%02u", $i);
										if($i == $minute)
											$selected = array('value'=>$i, 'name'=>$value);
										$values .= '<dd><a href="#" rel="'.$i.'">'.$value.'</a></dd>';
									}
								?>
								<span><a style="width: 44px; border: 1px solid #8A8B8C" class="arrowed validate" href="#"><?=$selected['name'] ?></a></span>
								<dl>
									<?=$values ?>
								</dl>
								<input type="hidden" id="pick_up_minute" name="pick_up[minute]" value="<?=$selected['value'] ?>" />
							</li>
						</ul>
					</td>
				</tr>
				<? endif; ?>
				<? if($vars['discount']): ?>
				<tr>
					<td colspan="2"></td>
					<td><strong>Product Discount</strong></td>
					<td class="price" id="container_discount"><?=price($vars['discount']*(-1)) ?></td>
					<td></td>
				</tr>
				<? endif; ?>
				<? if($vars['multibuy_discount']): ?>
				<tr>
					<td colspan="2"></td>
					<td><strong>Multi Buy Discount</strong></td>
					<td class="price" id="container_multibuy_discount"><?=price($vars['multibuy_discount']*(-1)) ?></td>
					<td></td>
				</tr>
				<? endif; ?>
				<tr>
					<td colspan="2"></td>
					<td><strong>SubTotal</strong><br/>(Excludes P&P)</td>
					<td class="grandPrice" id="container_total"><?=price($vars['total']) ?></td>
					<td></td>
				</tr>
				<tr>
					<td colspan="2"></td>
					<td><strong>Postage & Packing<br/>(<?=htmlentities($country, ENT_NOQUOTES, 'UTF-8')?>)</strong></td>
					<td class="price" id="container_postage_packing"><?=price($vars['packing']+$vars['shipping']) ?></td>
					<td>
						<a href="#" id="change_country" class="redButton" style="padding: 3px 6px;">change</a>
						<div style="position: relative; float: right; width: 1px;">
							<div id="deliveryCountry">
								<h5>Delivery Country</h5>
								<p>Select a delivery country.</p>
								<ul class="selector" style="margin-bottom: 16px;">
									<li style="width: 187px;">
										<?
											$options = '';
											$selected = array('value'=>'', 'name'=>'Country');
											while($row = $countries->FetchRow())
											{
												if($session->session->fields['delivery_country_id']+0 == $row['id'])
													$selected = array('value'=>$row['id'], 'name'=>htmlentities($row['name'], ENT_NOQUOTES, 'UTF-8'));
													
												$options .= '<dd><a href="#" rel="'.$row['id'].'">'.htmlentities($row['name'], ENT_NOQUOTES, 'UTF-8').'</a></dd>';
											}
										?>
										<a style="width: 180px; border: 1px solid #8A8B8C" class="arrowed" href="#" tabindex="5"><?=$selected['name'] ?></a>
										<dl><?=$options ?></dl>
										<input type="hidden" id="country_id" name="country_id" value="<?=$selected['value'] ?>" />
									</li>
								</ul>
								<p><br /><br /><a href="#" id="submit_country" class="redButton" style="padding: 3px 6px;">Select</a></p>
							</div>
						</div>
						<script language="javascript" type="text/javascript">
						/* <![CDATA[ */
							$(document).ready(function(){
								$('#change_country').click(function(){
									$('#deliveryCountry').show('normal');
									return false;
								});
								$('#submit_country').click(function(){
									$('#delivery_country_id').val($('#country_id').val());
									$('#frmDelivery').submit();
								});
							});
						/* ]]> */
						</script>
					</td>
				</tr>
				<tr>
					<td colspan="2"></td>
					<td style="color: #EC2027"><strong>Final Total</td>
					<td class="grandPrice" id="container_final_total"><?=price($vars['total']+$vars['packing']+$vars['shipping']) ?></td>
					<td></td>
				</tr>
			</tfoot>
		</table>
		<div>
			<a href="<?=$config['dir'] ?>" class="redBackDoubleArrow" style="float: left">continue shopping</a>
			<? if($session->session->fields['delivery_country_id']+0 && !count($restricted_products) && !count($low_stock_products) && count($rows)): ?>
				<input type="submit" id="frmSubmit" name="submit" value="checkout" class="redDoubleArrow" style="float: right;" />
			<? endif; ?>
			<br clear="all"/>
		</div>
	</form>
	<form id="frmDelivery" action="<?=$config['dir'] ?>cart?act=saveDelivery" method="post">
		<input type="hidden" id="delivery_country_id" name="country_id" value="" />
	</form>
	<br/>
	<br/>
	<br/>
	<br/>
	<? if(count($restricted_products) || count($low_stock_products) || count($warnings)): ?>
	<script language="javascript" type="text/javascript">
	/* <![CDATA[ */
		$(document).ready(function(){
			$.colorbox({
				href:'#advancedSearch'
				,opacity: 0.3
				,width:544
				,close:false
				,inline:true
			}); 
		});
	/* ]]> */
	</script>
	<div id="restriction_products" style="display: none;">
		<form id="advancedSearch" action="#" method="post" class="advancedForm">
		<?
			$alerts = array();
			if(count($restricted_products))
			{
				$alert = '<h3>Shipping Restrictions</h3><p>Unfortunately, we cannot ship these items to your country:<br /><br /></p>';
				foreach($restricted_products as $row)
					$alert .= '<p>'.htmlentities($row['name'], ENT_NOQUOTES, 'UTF-8').' - '.price($row['cart_price']).'</p>';
				$alert .= '<p><br />Please either delete these items or choose another shipping country.</p>';
				$alerts[] = $alert;
			}
			if(count($low_stock_products))
			{
				$alert = '<h3>Stock Restriction</h3><p>Unfortunately, these items are out of stock:<br /><br /></p>';
				foreach($low_stock_products as $row)
					$alert .= '<p>'.htmlentities($row['name'], ENT_NOQUOTES, 'UTF-8').' - '.price($row['cart_price']).'</p>';
				$alert .= '<p><br />Please delete these items from your basket.</p>';
				$alerts[] = $alert;
			}
			foreach($warnings as $row)
				$alerts[] = '<h3>'.htmlentities($row['title'], ENT_NOQUOTES, 'UTF-8').'</h3><p>'.$row['description'].'</p>';
				
			echo implode('<br /><br />', $alerts);
		?>
			<p class="submit"><input type="button" class="redDoubleArrow cClose" value="Close"></p>
		</form>
	</div>
	<? endif; ?>
</article>