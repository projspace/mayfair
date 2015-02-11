<link rel="stylesheet" href="<?= $config['layout_dir'] ?>css/tango/skin.css" />
<script type="text/javascript" src="<?= $config['layout_dir'] ?>js/jcarousel.min.js"></script>
<div id="content-wrapper" class="yui3-g">
    <div id="fullcontent" class="yui3-u press">
        <ul class="press-pager clearfix">
            <li class="float-left" style="padding-left:20px;"><?= strtoupper($types[$type]) ?></li>
        </ul>
        <div class="press-carousel-container">
            <ul id="slider_container" class="jcarousel-skin-tango press-carousel">
                <? while($row = $images->FetchRow()): if($row['imagetype']): ?>
                <li><a href="<?= $config['dir'] ?>images/press/<?= $row['id'] ?>.<?= $row['imagetype'] ?>" class="fancybox"><img src="<?= $config['dir'] ?>images/press/big/<?= $row['id'] ?>.<?= $row['imagetype'] ?>" /></a></li>
                <? endif;endwhile ?>
            </ul>
        </div>
        <h2><?= $press['title'] ?></h2>
        <div class="content">
            <?= $press['content'] ?>
        </div>
        <div class="tools">
            <a href="<?= $config['dir'].$type ?>">BACK TO <?= strtoupper($types[$type]) ?> HOME</a>
            <div class="share">
                <span>SHARE</span>
                <a href="http://www.facebook.com/share.php?u=<?= urlencode('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']) ?>" class="facebook">Facebook</a>
                <a href="https://twitter.com/intent/tweet?url=<?= urlencode('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']) ?>" class="twitter">Twitter</a>
                <a href="http://pinterest.com/pin/create/button/?url=<?= urlencode('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']) ?>" class="pinterest">Pinterest</a>
                <a href="https://plus.google.com/share?url=<?= urlencode('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']) ?>" class="google">Google</a>
            </div>
        </div>
        <div class="space">
            <? if($related): ?>RELATED <?= strtoupper($types[$type]) ?><? endif ?>
            <? if($link = $press['link']): ?>
            <div class="float-right">
                <a href="<?= $link ?>"><img src="<?= $config['dir'] ?>layout/templates/bloch/images/buy-style.jpg" alt="" /></a>
            </div>
            <? endif ?>
        </div>
        <? if($related): ?>
        <ul class="press-list clearfix" style="margin-top:50px;">
            <? foreach($related as $row): ?>
            <li style="min-height:inherit;">
                <h4><strong><?= date('d.m.y', $row['date']) ?></strong> <?= strtoupper($row['title']) ?></h4>
                <a href="<?= $config['dir'] ?><?= $type ?>/<?= $row['id'] ?>" class="press-img"><img src="<?= $config['dir'] ?>images/press/list/<?= $row['id'] ?>.<?= $row['imagetype'] ?>" width="207" height="294" title="<?= $row['title'] ?>" alt="<?= $row['title'] ?>"/></a>
            </li>
            <? endforeach ?>
        </ul>
        <br /><br />
        <? endif ?>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('#slider_container').jcarousel({
            scroll: 1
        });
        <? if(count($images) == 1): ?>
//        setTimeout(function(){
//            $('.jcarousel-next').hide();
//            $('.jcarousel-prev').hide();
//        }, 100);
        <? endif ?>

        var $ul = $('ul.press-list');
        if($ul.length){
            var w = $ul.children().length*$ul.find('li:first').width();
            $ul.css({position:'relative', left: ($ul.parent().width()-w)/2, marginLeft: 0, width: w+100});
        }

        $('.share a').click(function(){
            window.open(this.href, "pop", "status=no,scrollbars=no,width=600,height=300");
            return false;
        })
    });
</script>