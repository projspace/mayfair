<style type="text/css">
html { background-color: #C2C2C2; }
.main { width: auto; padding: 0; }
.main .block { padding-bottom: 0; }
.price { float: left; margin-top: 12px; }
.price em { font-family: Georgia,"Times New Roman",Times,serif; font-size: 15px; }
</style>
<!-- product preview -->
<div id="product-preview">
    <div class="preview-wrap" id="product">
        <div class="block pop-title"><?=htmlentities(strtoupper($product['name']), ENT_NOQUOTES, 'UTF-8') ?><?=(trim($product['code']) != '')?' <em>'.htmlentities(strtoupper($product['code']), ENT_NOQUOTES, 'UTF-8').'</em>':'' ?></div>
        <? if(count($images)): ?>
        <div class="block product-frame">
            <img src="<?= $images[0]['view'] ?>" title="<?= htmlentities($product['name'], ENT_COMPAT, 'UTF-8') ?>" alt="<?= htmlentities($product['name'], ENT_COMPAT, 'UTF-8') ?>" width="440" />
        </div>
        <? endif; ?>
        <?= $product['short_description'] ?>

        <form method="post" action="<?=$config['dir'] ?>add" class="options">
            <input type="hidden" name="category_id" value="<?=$product['category_id'] ?>" />
            <input type="hidden" name="product_id" value="<?=$product['id'] ?>" />
            <input type="hidden" name="source" value="" id="source" />

            <div class="block">
                <div class="options">
                <? if($_REQUEST['option_id']+0): ?><input type="hidden" id="initial_option_id" name="initial_option_id" value="<?=$_REQUEST['option_id'] ?>"><? endif; ?>
                <? if($_REQUEST['quantity']+0): ?><input type="hidden" id="initial_quantity" name="quantity" value="<?=$_REQUEST['quantity'] ?>"><? endif; ?>
                </div>
            </div>
            <div class="block buttons">
                <? if(!$product['hide_price']): ?>
                <div class="price">
                    <? if($product['price_old']+0 > 0): ?>
                    <em style="text-decoration: line-through;"><?=price($product['price_old']) ?></em> <em id="product_price_container"><?=price($product['price']) ?></em>
                    <? else: ?>
                    <em id="product_price_container"><?=($product['min_price']+0 != $product['max_price']+0)?price($product['price']+$product['min_price']).' to '.price($product['price']+$product['max_price']):price($product['price']) ?></em>
                    <? endif; ?>
                </div>
                <? endif; ?>
                <? if(!$product['hidden'] && !$product['hide_add_cart']): ?><a href="#" class="submit btn green-btn fl-right omega">Add to Shopping Cart</a><br clear="all"/><? endif; ?>
                <a href="#" class="wishlist btn green-btn fl-right omega">Save to wish list</a><br clear="all"/>
                <? if($user_session->check() && $elems->qryPendingGiftListCount()): ?>
                    <a href="#" class="guest btn green-btn fl-right omega">Add to Gift Registry</a><br clear="all"/>
                <? endif; ?>
            </div>
        </form>
    </div>
</div>
<!-- End Product Preview -->
<? $elems->placeholder('script')->captureStart() ?>
<script language="javascript" type="text/javascript">
/* <![CDATA[ */
	function resizeFB(){
		$('#fancybox-content', parent.document).css('height', ($('#content').height()+20)+'px');
		parent.$.fancybox.center(true);
	}

	$(document).ready(resizeFB);
	$(window).load(resizeFB);
/* ]]> */
</script>
<script type="text/javascript">
    (function($){$(function(){
        var $form = $("#product form.options");
        var isFormComplete = function() {
            var complete = ($('#option_id').length && parseInt($('#quantity').val()))?true:false;
            return complete;
        };
        var getFormData = function(){
            var data = {};
            data['product_id'] = <?=$product['id']+0 ?>;
            data['category_id'] = <?=$product['category_id']+0 ?>;
            data['option_id'] = $('#option_id').val();
            data['source'] = $('#product form.options #source').val();
            $form.find('.options select').each(function() {
                data[$(this).attr('name')] = $(this).val();
            });
            return data;
        };
        var fancyModifications = function(){
            $("#product form.options .custom-select:odd").addClass('omega fl-right');
            resizeFB();
        }
        $form.find('a.submit').unbind('click').click(function(){
            if ( !$(this).is('.disabled') ){
                var $p = $('<p class="message">Adding to basket</p>');
                $(this).hide().before($p);
                var data = getFormData();
                $.ajax({
                    url: '<?=$config['dir'] ?>ajax/act_AddCart.php',
                    type: 'post',
                    dataType: 'json',
                    data: data,
                    success: function(json){
                        if(json.status === true)
                            $p.html('Product added to basket.').addClass('success');
                        else if(json.status === 'clear_gift_registry')
                        {
                            if(confirm(json.message))
                            {
                                $.ajax({
                                    async: false,
                                    url: '<?=$config['dir'] ?>ajax/act_ClearCart.php',
                                    type: 'post'
                                });
                                $p.remove();
                                $form.find('a.submit').click();
                            }
                            else
                                $p.remove();
                        }
                        else
                            $p.html(json.message).addClass('failed');

                        window.scrollTo(0,0);
                        parent.$.cart.reload();
                        parent.$.cart.show();
                        parent.$.cart.display('#cart .contents li:last', '-');

                        //reset form
                        $('#option_id').val('');
                        $('#product form.options #source').val('');
                        $form.find('.options select').val('');
                        $form.trigger('change');
                    }
                });
            }
            return false;
        });

        $form.find('a.wishlist').unbind('click').click(function(){
            if ( !$(this).is('.disabled') ){
                var $p = $('<p class="message">Adding to wishlist</p>');
                $(this).hide().before($p);
                var data = getFormData();
                $.ajax({
                    url: '<?=$config['dir'] ?>ajax/act_AddWishlist.php',
                    type: 'post',
                    dataType: 'json',
                    data: data,
                    success: function(json){
                        if(json.status === true)
                            $p.html('Product added to wishlist.').addClass('success');
                        else if(json.status === 'login')
                            $p.html('Please <a href="<?=$config['dir'] ?>login?ajax=1&return_url=<?=urlencode($config['dir'].'wishlist/insert/'.$product['id'].'/') ?>'+$('#option_id').val()+'<?=urlencode('/') ?>'+$('#quantity').val()+'">sign in</a> or <a href="<?=$config['dir'] ?>register?ajax=1&redirect_url=<?=urlencode($config['dir'].'wishlist/insert/'.$product['id'].'/') ?>'+$('#option_id').val()+'<?=urlencode('/') ?>'+$('#quantity').val()+'<?=urlencode('?ajax=1') ?>">sign up</a> in order to save the product to your wishlist.').addClass('failed');
                        else
                            $p.html(json.message).addClass('failed');
                    }
                });
            }
            return false;
        });

        $form.find('a.guest').unbind('click').click(function(){
            var $this = this;
            if ( !$(this).is('.disabled') ){
                $.ajax({
                    url: '<?=$config['dir'] ?>ajax/qry_GiftLists.php',
                    type: 'get',
                    dataType: 'json',
                    success: function(json){
                        if(json.status === true)
                        {
                            $($this).after(json.message);
                            if($('#gift_list_id option').length == 2)
                            {
                                $('#gift_list_id').val($('#gift_list_id option:eq(1)').attr('value'));
                                $('#gift_list_id').change();
                            }
                            else
                                $('#gift_list_id').customSelect();
                        }
                        else if(json.status === 'login')
                            $('#signupin .in').attr('href','<?=$config['dir'] ?>login?ajax=1').click();
                        else
                            $p.html(json.message).addClass('failed');
                    }
                });
            }
            return false;
        });
        $('#gift_list_id').live('change', function(){
            var $this = this;
            var $p = $('<p class="message">Adding to guest list</p>');
            $(this).hide().before($p);
            var data = getFormData();
            data['list_id'] = $(this).val();
            $.ajax({
                url: '<?=$config['dir'] ?>ajax/act_AddGuestlist.php',
                type: 'post',
                dataType: 'json',
                data: data,
                success: function(json){
                    if(json.status === true)
                    {
                        $($this).closest('.custom-select').hide();
                        $p.html('Item has been added to gift registry.').addClass('success');
                    }
                    else if(json.status === 'login')
                        $('#signupin .in').attr('href','<?=$config['dir'] ?>login?ajax=1').click();
                    else
                        $p.html(json.message).addClass('failed');
                }
            });
        });
        $("#product form.options .options select").live('change', function(){
            if ( isFormComplete() )
            {
                $form.find('a.submit, a.wishlist, a.guest').css('opacity', 1).removeClass('disabled').show();
                return false;
            }
            else
                $(this).parents('form').trigger('changed');
        });

        var init = function(){
            $("#product form.options .options select").customSelect();
            $('#product form.options #source').val('');

            $("#product form.options .options select.color").change(function(){
                $('#product form.options #source').val('color');
            });

            var price = parseFloat($("#product form.options .options #product_price").val());
            var price_min = parseFloat($("#product form.options .options #product_price_min").val());
            var price_max = parseFloat($("#product form.options .options #product_price_max").val());
            if(!isNaN(price))
                $('#product_price_container').text('$'+price.toFixed(2));
            else if(!isNaN(price_min) && !isNaN(price_max))
                $('#product_price_container').text('$'+price_min.toFixed(2)+' to '+'$'+price_max.toFixed(2));
            else
                $('#product_price_container').text('Price not available');

            if ( !isFormComplete() )
                $form.find('a.submit, a.wishlist, a.guest').css('opacity', .25).addClass('disabled').show();
            else
                $form.find('a.submit, a.wishlist, a.guest').css('opacity', 1).removeClass('disabled').show();

            fancyModifications();
        };

        $form.bind('change', function(){
            var data = getFormData();
            var $this = $(this);
            $this.fadeTo('fast',.25);
            $.ajax({
                url: '<?=$config['dir'] ?>ajax/qry_ProductOptions.php',
                type: 'post',
                data: data,
                dataType: 'html',
                success: function(data){
                    $("#product form.options .options").html(data);
                    init();
                    $this.fadeTo('fast', 1);
                }
            });
        });

        $form.trigger('change');

    })})(jQuery);
</script>
<? $elems->placeholder('script')->captureEnd() ?>
