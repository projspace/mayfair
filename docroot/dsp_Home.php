<style type="text/css">
    .home-list li .up { margin-top: 0 !important; }
    .home-list li .rate-links { position: static; margin-top: 5px; padding: 0; text-align: center; }
    .home-list li .rate-links .button { float: none; display: inline-block; }
</style>
<div class="banner">
    <div id="slideshow" class="left-banner">
        <ul>
        <? foreach($slides as $image): ?>
            <li>
                <img src="<?= $config['dir'] ?>images/page/view/image_<?= $image['id'].'.'.$image['image_type'] ?>" alt="banner-image" width="984" height="545">
                <div class="info">
                    <h2><?= $image['metadata']['title'] ?></h2>
                    <p><?= $image['metadata']['description'] ?></p>
                </div>
                <a href="<?= $image['metadata']['url'] ?>">&nbsp;</a>
            </li>
        <? endforeach; ?>
        </ul>
    </div>
    <ul class="right-banner">
        <li class="free-shipping"><a href="<?= $config['dir'] ?>shipping-policy"><img src="<?= $config['layout_dir'] ?>images/freeshipping.jpg" alt="free shipping" title="free shipping" /></a></li>
        <!--<li><a href="<?= $config['dir'] ?>shop-by-brand/5"><img src="/images/sferra_ad.jpg" alt="sferra" /></a></li>-->
        <li><a href="<?= $config['dir'] ?>gift-bridal-registry"><img src="/images/gbr.jpg" alt="gift and bridal registry" /></a></li>
        <li class="sign-up">
            <h2>KEEP IN TOUCH</h2>
            <p>Exclusive offers when you subscribe to our newsletter</p>
            <iframe src="<?=$config['dir'] ?>homefs" scrolling="no" width="204" height="110" style="margin-top: 15px; overflow: hidden;"></iframe>
            <!--
            <ul>
                <li><input type="text" name="name" value="NAME" onfocus="if(this.value==this.defaultValue)this.value=''" onblur="if(this.value=='')this.value=this.defaultValue;" /></li>
                <li><input type="text" name="email_1" value="EMAIL" onfocus="if(this.value==this.defaultValue)this.value=''" onblur="if(this.value=='')this.value=this.defaultValue;" /></li>
                <li><input type="submit" value="SIGN UP" class="button"/></li>
            </ul>
            -->
        </li>
    </ul>
</div>
<div class="product-gallary">
    <?= $elems->content() ?>
    <ul class="home-list">
    <?
        $index = 0;
        while($row = $featured->FetchRow())
        {
            $index++;
            $name = array();
            if(($var = trim($row['code'])) != '')
                $name[] = $var;
            if(($var = trim($row['name'])) != '')
                $name[] = $var;
            $name = implode(' - ', $name);

            if(trim($row['image_type']) != '')
            {
                switch($category['listing_type'])
                {
                    case 'horizontal':
                        $image = '<img src="'.$config['dir'].'images/product/listing_horizontal/'.$row['image_id'].'.'.$row['image_type'].'" alt="" width="215" height="137" />';
                        break;
                    case 'vertical':
                        $image = '<img src="'.$config['dir'].'images/product/listing_vertical/'.$row['image_id'].'.'.$row['image_type'].'" alt="" width="215" height="274" />';
                        break;
                    default:
                        $image = '<img src="'.$config['dir'].'images/product/'.$row['image_id'].'.'.$row['image_type'].'" alt="" width="282" height="282" />';
                        break;
                }
            }
            else
                $image = '<img src="'.$config['dir'].'images/product/placeholder.jpg" alt="" width="282" height="282" />';

            echo '
                <li '.(($index%4 == 0)?'class="omega"':'').'>
                    <div class="title">
                        <a href="'.product_url($row['id'], $row['guid']).'"><h3>'.strtoupper($name).'</h3>
                        '.($row['hide_price']?'':'<span class="rate">'.price($row['price']).'</span>').'</a>
                    </div>
                    <a href="'.product_url($row['id'], $row['guid']).'" class="up">'.$image.'</a>
                    <div class="rate-links">'.($row['hide_quick_view']?'':'<a href="'.quick_product_url($row['id'], $row['guid']).'" class="button bg-light-grey quick-view">preview</a>').($row['hide_more_details']?'':' <a href="'.product_url($row['id'], $row['guid']).'" class="button bg-golden">DETAIL</a>').'</div>
                </li>';
        }
    ?>
    </ul>
</div>
<? $elems->placeholder('script')->captureStart() ?>
<script language="javascript" type="text/javascript">
$(document).ready(function(){
	$(".home-list").find('li:nth-child(3n+1)').each(function(){
        var max = [];
        max[max.length] = $('.title', $(this)).height();
        max[max.length] = $('.title', $(this).next()).height();
        max[max.length] = $('.title', $(this).next().next()).height();
        max[max.length] = $('.title', $(this).next().next().next()).height();
        max = Math.max(max[0], max[1], max[2]);

        $('.title', $(this)).css('height', max+'px');
        $('.title', $(this).next()).css('height', max+'px');
        $('.title', $(this).next().next()).css('height', max+'px');
        $('.title', $(this).next().next().next()).css('height', max+'px');
    });
});
</script>
<? $elems->placeholder('script')->captureEnd() ?>