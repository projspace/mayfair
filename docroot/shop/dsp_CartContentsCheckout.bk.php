<script language="javascript" type="text/javascript">
/* <![CDATA[ */
	<?
		if(is_array($services))
			echo 'var services = '.json_encode($services).';';
		else
			echo 'var services = {};';
	?>
	$(document).ready(function(){
		$('a.submit').unbind('click').click(function(){
			$('#return_url').val('');
			$('#cart-form').submit();
			return false;
		});
		
		$('.delivery_service_code').click(function(){
			var delivery_code = $.trim($(this).val());
			$.ajax({
				url: '<?=$config['dir'] ?>ajax/act_UpdateShippingService.php',
				type: 'post',
				dataType: 'json',
				data: 'delivery_service_code='+delivery_code,
				success: function(json){
					if(json.status)
					{
						if(services[delivery_code] != undefined)
						{
							$('#delivery_warning').text(services[delivery_code]['warning']);
							$('#total_price').text('$'+(parseFloat(services[delivery_code]['price'])+<?=$vars['total']+$vars['packing']+$vars['tax'] ?>).toFixed(2));
							$("#btnSubmit").show();
						}
					}
					else
						$("#btnSubmit").hide();
				}
			});
			//$('#cart-form').attr('action', '<?=$config['dir'] ?>checkout?act=saveShippingService').submit();
		});
		
		if($('.delivery_service_code:checked').length == 0) //default upg ground
		{
			if(<?=($flat_rate_shipping && !$shippable)?'1':'0' ?>)
				$('.delivery_service_code[value="00"]').click();
			else
				$('.delivery_service_code[value="03"]').click();
		}
	});
/* ]]> */
</script>

<style type="text/css">
	#shopping-cart h1 { margin-bottom: 11px; }
	ul.shipping { display: inline-block; margin-left: 3em; }
	ul.shipping li { border: none; }
	.delivery_service_code { margin-right: 5px; }
</style>

<div id="content-wrapper" class="yui3-g">
	<aside id="sidebar" class="yui3-u related"></aside>
	<div id="content" class="yui3-u">
		<article class="content-box" id="shopping-cart">
			<div class="inner">
				<h1>Your shopping cart</h1>
				<p><strong>Stage 2 - please choose shipping speed (default is UPS Ground)</strong></p>
				<form method="post" action="<?=$config['dir'] ?>checkout?act=<?=$user_session->check()?'paymentAccount':'payment' ?>" class="std-form" id="cart-form">
					<ul class="taxes clearfix">
						<li>
							<? if(!$shippable): ?><span class="price"><?=price($vars['shipping']) ?></span><? endif; ?>
							<label>Shipping <br /><a href="<?=$config['dir'] ?>page/41?ajax=1" class="fancybox">more info</a></label>
							<? if($vars['shipping'] !== false): ?>
								<input type="hidden" value="00" name="delivery_service_code" class="delivery_service_code"/>
								<ul class="shipping">
								<?
									if($services)
										foreach($services as $code=>$row)
										{
											if($session->session->fields['delivery_service_code'] == $code)
												echo '<li><input type="radio" value="'.$code.'" name="delivery_service_code" class="delivery_service_code" checked="checked">'.htmlentities($row['name'].' - '.price($row['price']), ENT_NOQUOTES, 'UTF-8').'</li>';
											else
												echo '<li><input type="radio" value="'.$code.'" name="delivery_service_code" class="delivery_service_code">'.htmlentities($row['name'].' - '.price($row['price']), ENT_NOQUOTES, 'UTF-8').'</li>';
										}
								?>
								</ul>
							<? endif; ?>
							<? if(trim($delivery_service['warning'])): ?>
								<div style="margin-left: 3em; margin-top: 4px;" id="delivery_warning">
									<?=trim($delivery_service['warning']) ?>
									<? if($vars['shipping'] === false): ?><a class="btn-red" style="padding: 0 5px; float: right;" href="<?=$config['dir'] ?>checkout">Back</a><? endif; ?>
								</div>
							<? endif; ?>
						</li>
						<li>
							<span class="price"><?=price($vars['total']+$vars['packing']) ?></span>
							<label>Goods</label>
						</li>
						<li>
							<? if($vars['tax'] !== false): ?>
							<span class="price">+<?=price($vars['tax']) ?></span>
							<? else: ?>
							<span class="price" style="color: #BA695A;">unknown</span>
							<? endif; ?>
							<label>Tax</label>
						</li>
						<li class="total">
							<span class="price" id="total_price"><?=price($vars['total']+$vars['packing']+$vars['shipping']+$vars['tax']) ?></span>
							<label>Total</label>
						</li>
					</ul>
					<? if($vars['tax'] !== false): ?>
					<p class="submit-buttons">
						<a href="#" class="btn-red submit" id="btnSubmit" <? if($vars['shipping'] === false): ?>style="display: none;"<? endif; ?>>Pay By Credit Card finalize order  (Stage 3)</a>
					</p>
					<? endif; ?>
				</form>
				<p>Clicking on Pay by Credit card will transfer you to authorize.net who will bill your credit card.With Authorize.Net, you can be confident that your data is secure. They utilize industry-leading technologies and protocols, such as 128-bit Secure Sockets Layer (SSL) and are compliant with a number of government and industry security initiatives.</p>
			</div>
		</article>
	</div>
</div>
<!-- Google Code for Add to Basket Remarketing List -->
<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 1012737394;
var google_conversion_language = "en";
var google_conversion_format = "3";
var google_conversion_color = "ffffff";
var google_conversion_label = "8XNJCK7XxgIQ8sr04gM";
var google_conversion_value = <?=number_format($vars['total']+$vars['packing']+$vars['shipping'], 2, '.', '') ?>;
/* ]]> */
</script>
<script type="text/javascript" src="http://www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="http://www.googleadservices.com/pagead/conversion/1012737394/?label=8XNJCK7XxgIQ8sr04gM&amp;guid=ON&amp;script=0"/>
</div>
</noscript>