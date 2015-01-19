<div class="top-space">
    <div class="block">
        <!-- Sidebar -->
        <div class="sidebar" id="sidebar">
            <div class="sidebar-products  border-lite">
                <h2>YOU MAY ALSO LIKE</h2>
                <ul class="products">
                <?
                    foreach($related_products as $row)
                    {
                        if($row['image_type'])
                            $image = $config['dir'].'images/product/'.$row['image_id'].'.'.$row['image_type'];
                        else
                            $image = $config['dir'].'images/product/placeholder.jpg';

                        echo '<li>
                            <h2><a href="'.product_url($row['id'], $row['guid']).'"><span>'.htmlentities(strtoupper($row['name']), ENT_NOQUOTES, 'UTF-8').'</span><em>'.price($row['price']).'</em></a></h2>
                            <a href="'.product_url($row['id'], $row['guid']).'"><img src="'.$image.'" alt="product" width="239"/></a>
                        </li>';
                    }
                ?>
                </ul>
            </div>
        </div>
        <!-- End Sidebar -->

        <div class="right-container-sub" id="right-container-sub">
            <h2 class="change-color">YOUR SHOPPING CART</h2>
            <div id="product-detail">
                <form method="post" action="<?=$config['dir'] ?>cart?act=saveDetails" id="cart-form">
                    <input type="hidden" id="return_url" name="return_url" value="" />
                    <table class="product-detail">
                        <tr class=" heading">
                            <td class="first">Product</td>
                            <td>Details</td>
                            <td></td>
                            <td>price</td>
                        </tr>
                        <?
                            foreach($rows as $row)
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

                                if($vars['promotional_discount_type'] == 'percent')
                                {
                                    if($row['promotional_discount']+0)
                                        $price = '<span style="text-decoration: line-through;">'.price($row['cart_price']).'</span><br /><span style="font-weight: normal;">after discount</span><br />'.price($row['cart_price'] - $row['promotional_discount']);
                                    else
                                        $price = price($row['cart_price']).'<br /><span style="font-weight: normal;">no discount</span>';
                                }
                                else
                                    $price = price($row['cart_price']);

                                echo '
                                    <tr>
                                        <td class="first">
                                            <a href="#"><span class="thumb-frame"><img src="'.$image.'" alt="product" width="124" height="124" /></span></a>
                                            <p>'.implode('<br />', $name).'</p>
                                        </td>
                                        <td>'.implode('</p><p>', $options).'</td>
                                        <td>
                                            <a href="'.$config['dir'].'cart/remove/'.$row['cart_id'].'" class="link">Remove</a>
                                            <a href="'.$config['dir'].'wishlist/add/'.$row['cart_id'].'" class="link">Add to wishlist</a>
                                            <a href="'.product_url($row['id'], $row['guid']).'" class="link">Update</a>
                                        </td>
                                        <td>'.$price.'</td>
                                    </tr>';
                            }
                        ?>
                        <tr>
                            <td class="first">
                                <a href="<?=$config['dir'] ?>promotional-code?ajax=1" class="fancybox label">Promotional Code</a>
                                <p><input type="text" class="clearable" id="discount_code" name="discount_code" value="<?=(trim($session->session->fields['discount_code']) != '')?$session->session->fields['discount_code']:'Enter your code here' ?>" placeholder="Enter your code here" /></p>
                            </td>
                            <td><p><a href="#" class="apply" id="btnApply">Apply</a></p></td>
                            <td></td>
                            <td><?=(($vars['promotional_discount'] == 0)?'-':'').price($vars['promotional_discount']*(-1)) ?></td>
                        </tr>
                        <? if($gift_voucher_increment_visible['value']+0): ?>
                        <tr>
                            <td class="first">
                                <a href="<?=$config['dir'] ?>gift-voucher?ajax=1" class="fancybox label">Gift voucher</a>
                                <p>
                                    <select id="gift_voucher" name="gift_voucher" <? if(MOBILE_DEV):?>class="mobile"<? endif;?>>
                                        <option value="">Please select amount</option>
                                        <?
                                            for($i=0;$i<=$gift_voucher_increment_count['value'];$i++)
                                            {
                                                $value = $gift_voucher_start['value'] + $i*$gift_voucher_increment_value['value'];

                                                if($value == $session->session->fields['gift_voucher'])
                                                    echo '<option value="'.$value.'" selected="selected">'.price($value).'</option>';
                                                else
                                                    echo '<option value="'.$value.'">'.price($value).'</option>';
                                            }
                                        ?>
                                    </select>
                                </p>
                            </td>
                            <td><p><a href="#" class="apply" id="btnApply">Apply</a></p></td>
                            <td></td>
                            <td>+<?=price($session->session->fields['gift_voucher']) ?></td>
                        </tr>
                        <? endif; ?>
                        <? if($packing_visible['value']+0 && count($rows)): ?>
                        <tr>
                            <td class="first">
                                <div class="custom-checkbox">
                                    <input type="checkbox" id="packing" name="packing" value="1" <? if($session->session->fields['packing'] !== null): ?>checked="checked"<? endif; ?> />
                                    <label for="packing" style="font-size: 15px; line-height: normal; position: relative;"><span style="position: absolute; top: 0; width: 300px;">Please select this option to receive our complimentary Mayfair House signature gift wrap and enclosure card</span></label>
                                </div>
                            </td>
                            <td></td>
                            <td></td>
                            <td>+<?=price($vars['packing']) ?></td>
                        </tr>
                        <? if($session->session->fields['packing'] !== null): ?>
                        <tr>
                            <td class="first">
                                <a class="label">Gift Message</a>
                                <p><textarea id="gift_message" name="gift_message" rows="7" cols="30" style="margin-left: 18px;"><?=$session->session->fields['gift_message'] ?></textarea></p>
                            </td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <? endif; ?>
                        <? endif; ?>
                        <tr class="totalprice">
                            <td class="total-list">TOTAL</td>
                            <td></td>
                            <td></td>
                            <td><?=price($vars['total']+$vars['packing']+$vars['shipping']) ?></td>
                        </tr>
                    </table>
                    <div class="checkButton">
                        <input type="submit" class="submit" value="checkout" />
                        <input type="button" value="continue shopping" class="button2" onclick="javascript: window.location = '<?=$config['dir'] ?>'; return false;" />
                    </div>
                </form>
            </div>

            <? if(count($wishlist)): ?>
                <h2 class="change-color"><a href="#" id="btn_wishlist">Your wishlist</a></h2>
                <div id="product-detail" style="min-height: auto;">
                    <table class="product-detail" id="wishlist">
                    <?
                        foreach($wishlist as $row)
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
                                        <a href="'.$config['dir'].'wishlist/remove/'.$row['wish_id'].'" class="link">Remove</a>
                                    </td>
                                    <td>'.$price.'</td>
                                </tr>';
                        }
                    ?>
                    </table>
                </div>
            <? endif; ?>
        </div>
        <!-- content -->

        <!-- End Content -->

    </div>
</div>

<? $elems->placeholder('script')->captureStart() ?>
<script language="javascript" type="text/javascript">
/* <![CDATA[ */
	$(document).ready(function(){
		$('input.submit').unbind('click').click(function(){
			$('#return_url').val('');
			$('#cart-form').submit();
			return false;
		});

		$("#packing").click(function(){
			$('#return_url').val('<?=$config['dir'] ?>cart');
			$('#cart-form').submit();
			return false;
		});
	});
/* ]]> */
</script>
<script type="text/javascript">
/* <![CDATA[ */
	$(document).ready(function(){
		$('#btn_wishlist').click();
		$("#discount_code").keyup(function(){
			$.ajax({
				url: '<?=$config['dir'] ?>ajax/act_ValidateDiscountCode.php',
				type: 'get',
				dataType: 'json',
				data: 'discount_code='+$('#discount_code').val(),
				success: function(json){
					if(json.status)
						$("#discount_code").css('color', '#667D66');
					else
						$("#discount_code").css('color', '#B3351E');
				}
			});
		});
		$('#discount_code').keydown(function(event){
			if(event.keyCode == 13)
			{
				$('#return_url').val('<?=$config['dir'] ?>cart');
				$('#cart-form').submit();
				return false;
			}
		});
		$('#btnApply').click(function(event){
			$('#return_url').val('<?=$config['dir'] ?>cart');
			$('#cart-form').submit();
			return false;
		});
	});
/* ]]> */
</script>
<!-- Google Code for Add to Basket Remarketing List --
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
</noscript>-->
<? $elems->placeholder('script')->captureEnd() ?>