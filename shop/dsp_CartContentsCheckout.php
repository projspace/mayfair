<style type="text/css">
    table.product-detail { width: 100%; }
	table.product-detail td:last-child { width: 50px; text-align: right; }
    table.product-detail .shipping { margin: 10px; }
    table.product-detail .shipping .custom-radio label { font-size: 14px; }
</style>
<div id="checkOut">
    <div class="block">
        <div class="tab-wrapper">
            <h1><?=$page['name'] ?></h1>
            <ul class="tab-nav">
                <li><a href="<?=$config['dir'] ?>checkout" >addresses</a></li>
                <li class="active"><a href="#" >taxes</a></li>
                <li class="inactive"><a href="#" >payment</a></li>
                <li class="inactive"><a href="#" >thank you</a></li>
            </ul>
            <div id="tab-content">
                <form method="post" action="<?=$config['dir'] ?>checkout?act=<?=$user_session->check()?'paymentAccount':'payment' ?>" id="cart-form">
					<h3 class="capital">Your shopping cart</h3>
                    <? if(!($session->session->fields['last_gift_list_id']+0)): ?>
				    <p><strong>Please choose your shipping method</strong></p>
                    <? endif; ?>
                    <div class="detail-section detail-section-full">
                        <table class="product-detail">
                            <? if(!($session->session->fields['last_gift_list_id']+0)): ?>
                            <tr class="totalprice" style="border-top: none;">
                                <td class="first" colspan="2">
                                    <a href="<?=$config['dir'] ?>page/41?ajax=1" class="fancybox">Shipping</a><br />
                                    <? if($vars['shipping'] !== false): ?>
                                        <ul class="shipping">
                                        <?
                                            if($services)
                                                foreach($services as $code=>$row)
                                                {
                                                    $checked = ($session->session->fields['delivery_service_code'] == $code)?'checked="checked"':'';

                                                    echo '
                                                        <li>
                                                            <div class="custom-radio top-space">
                                                                <input type="radio" value="'.$code.'" '.$checked.' id="radio-'.$code.'" name="delivery_service_code" class="delivery_service_code">
                                                                <label for="radio-'.$code.'" class="'.(($session->session->fields['delivery_service_code'] == $code)?'checked':'').'">'.htmlentities($row['name'].' - '.price($row['price']), ENT_NOQUOTES, 'UTF-8').'</label>
                                                            </div><div class="clear"></div>
                                                        </li>';
                                                }
                                        ?>
                                        </ul>
                                    <? endif; ?>
                                    <? if(trim($delivery_service['warning'])): ?>
                                        <div id="delivery_warning">
                                            <?=trim($delivery_service['warning']) ?>
                                            <? if($vars['shipping'] === false): ?><a class="btn-red" style="padding: 0 5px; float: right;" href="<?=$config['dir'] ?>checkout">Back</a><? endif; ?>
                                        </div>
                                    <? endif; ?>
                                </td>
                                <td><? if(!$shippable): ?><?=price($vars['shipping']) ?><? endif; ?></td>
                            </tr>
                            <? endif; ?>
                            <tr class="totalprice" <?=($session->session->fields['last_gift_list_id']+0)?'style="border-top: none;"':'' ?><??>>
                                <td class="first"><a href="#">Merchandise</a></td>
                                <td></td>
                                <td><?=price($vars['total']+$vars['packing']) ?></td>
                            </tr>
                            <tr class="totalprice">
                                <td class="first"><a href="#">Tax</a></td>
                                <td></td>
                                <td><?=($vars['tax'] !== false)?'+'.price($vars['tax']):'unknown' ?></td>
                            </tr>
                            <tr class="totalprice">
                                <td class="total-list">TOTAL</td>
                                <td></td>
                                <td id="total_price"><?=price($vars['total']+$vars['packing']+$vars['shipping']+$vars['tax']) ?></td>
                            </tr>
                        </table>
                        <? if($vars['tax'] !== false): ?>
                        <div class="checkButton">
                            <input type="submit" class="submit" value="Pay By Credit Card finalize order  (Stage 3)" <? if($vars['shipping'] === false): ?>style="display: none;"<? endif; ?> />
                        </div>
                        <? endif; ?>
                    </div>
				</form>
            </div>
        </div>
    </div>
</div>

<? $elems->placeholder('script')->captureStart() ?>
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
<? $elems->placeholder('script')->captureEnd() ?>