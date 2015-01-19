<style type="text/css">
    .main-view .img { display: none; }
    .main-view .img:first-child { display: block; }
</style>
<div id="product-detail-page" class="top-space">
    <div class="block">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-products  border-lite">
                <h2>YOU MAY ALSO LIKE</h2>
                <ul class="products">
                <?
                    while($row = $related_products->FetchRow())
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
            <!--<div class="block add-block"> <a href="#"><img src="http://dummyimage.com/282x298/000/fff.jpg" alt="add" /></a> </div>-->
        </div>

        <!-- End Sidebar -->
        <div class="right-container-sub" id="product">
            <div class="main-view">
            <?
                foreach($images as $row)
                {
                    echo '<div class="img">';
                    if(!in_array($product['zoom'], array('', 'no')))
                        echo '<a href="'.$row['zoom'].'" class="MagicZoomPlus" rel="hint-text: Click for full screen or hover to zoom in; expand-size:original;"><img src="'.$row['view'].'" title="'.htmlentities($product['name'], ENT_COMPAT, 'UTF-8').'" alt="'.htmlentities($product['name'], ENT_COMPAT, 'UTF-8').'" width="'.$row['view_width'].'" height="'.$row['view_height'].'"  /></a>';
                    else
                        echo '<img src="'.$row['view'].'" width="550" title="'.htmlentities($product['name'], ENT_COMPAT, 'UTF-8').'" alt="'.htmlentities($product['name'], ENT_COMPAT, 'UTF-8').'"/>';
                    echo '</div>';
                }
                if(count($images) > 1)
                {
                    echo '<ul class="product-images">';
                    foreach($images as $row)
                    {
                        echo '<li><a href="#"><img src="'.$row['thumb'].'" alt="" /></a></li>';
                    }
                    echo '</ul>';
                }
            ?>
            </div>
            <div class="sub-sidebar">
                <h2><?=htmlentities(strtoupper($product['name']), ENT_NOQUOTES, 'UTF-8') ?></h2>
                <div class="top-space bottom-space"><?=$product['description'] ?></div>
                <? if(!$product['hide_price']): ?>
                    <? if($product['price_old']+0 > 0): ?>
                    <em style="text-decoration: line-through;"><?=price($product['price_old']) ?></em> <em id="product_price_container"><?=price($product['price']) ?></em>
                    <? else: ?>
                    <em id="product_price_container"><?=($product['min_price']+0 != $product['max_price']+0)?price($product['price']+$product['min_price']).' to '.price($product['price']+$product['max_price']):price($product['price']) ?></em>
                    <? endif; ?>
                <? endif; ?>


                <form method="post" action="<?=$config['dir'] ?>add" class="options">
                    <input type="hidden" name="category_id" value="<?=$product['category_id'] ?>" />
                    <input type="hidden" name="product_id" value="<?=$product['id'] ?>" />
                    <input type="hidden" name="source" value="" id="source" />

                    <div class="block">
                        <div class="options">
                        <? if($_REQUEST['option_id']+0): ?><input type="hidden" id="initial_option_id" name="initial_option_id" value="<?=$_REQUEST['option_id'] ?>"><? endif; ?>
                        <? if($_REQUEST['quantity']+0): ?><input type="hidden" id="initial_quantity" name="quantity" value="<?=$_REQUEST['quantity'] ?>"><? endif; ?>
                        </div>
                        <div class="buttons">
                            <? if(!$product['hidden'] && !$product['hide_add_cart']): ?><a href="#" class="submit btn green-btn fl-left omega large-btn">Add to Shopping Cart</a><? endif; ?>
                            <a href="#" class="wishlist btn green-btn fl-left omega large-btn">Save to wish list</a>
                            <? if($user_session->check() && $elems->qryPendingGiftListCount()): ?>
                                <a href="#" class="guest btn green-btn fl-left omega large-btn">Add to Gift Registry</a>
                            <? endif; ?>
                            <div class="clear"></div>
                        </div>
                    </div>
                </form>

                <!--<h4 class="top-space"><a href="#">SIZE GUIDE</a></h4>
                <h4 class="top-space"><a href="#">DELIVERY DETAILS</a></h4>-->
                <!--<div class="add-block"> <a href="#"><img src="http://dummyimage.com/282x298/000/fff.jpg" alt="ads"  /></a> </div>-->
            </div>
        </div>
        <!-- content -->

        <!-- End Content -->
    </div>
</div>
<? $elems->placeholder('script')->captureStart() ?>
<? if(!in_array($product['zoom'], array('', 'no'))): ?>
<!-- link to magiczoomplus.css file -->
<link href="<?=$config['dir'] ?>lib/magiczoomplus/magiczoomplus/magiczoomplus.css" rel="stylesheet" type="text/css" media="screen"/>
<!-- link to magiczoomplus.js file -->
<script src="<?=$config['dir'] ?>lib/magiczoomplus/magiczoomplus/magiczoomplus.js" type="text/javascript"></script>
<? endif; ?>

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
                        $.cart.reload();
                        $.cart.show();
                        $.cart.display('#cart .contents li:last', '-');

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
                            $p.html('Please <a class="signIn" href="<?=$config['dir'] ?>login?ajax=1&return_url=<?=urlencode($config['dir'].'wishlist/insert/'.$product['id'].'/') ?>'+$('#option_id').val()+'<?=urlencode('/') ?>'+$('#quantity').val()+'">sign in</a> or <a class="signUp" href="<?=$config['dir'] ?>register?ajax=1&redirect_url=<?=urlencode($config['dir'].'wishlist/insert/'.$product['id'].'/') ?>'+$('#option_id').val()+'<?=urlencode('/') ?>'+$('#quantity').val()+'<?=urlencode('?ajax=1') ?>">sign up</a> in order to save the product to your wishlist.').addClass('failed');
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
            $(this).hide().parent().before($p);
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