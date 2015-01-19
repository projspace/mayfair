<div id="checkOut">
    <div class="block">
        <div class="tab-wrapper">
            <h1><?=$page['name'] ?></h1>
            <ul class="tab-nav">
                <li class="inactive"><a href="#" >addresses</a></li>
                <li class="inactive"><a href="#" >taxes</a></li>
                <li class="inactive"><a href="#" >payment</a></li>
                <li class="active"><a href="#" >thank you</a></li>
            </ul>
            <div id="tab-content" style="min-height: 0;">
            <?
                $page = $elems->qry_Page(24);
                echo $page['content'];
            ?>
            </div>
        </div>
    </div>
</div>
<?
	$url = str_replace('https://', 'http://', $config['dir']).'google-code?order_id='.$_REQUEST['order_id'].'&sess_id='.$_REQUEST['sess_id'];
	foreach($_REQUEST as $key=>$value)
		if(strpos($key, '__utm') === 0)
			$url .= '&'.$key.'='.$value;
?>
<iframe style="width: 1px; height: 1px;" frameborder="0" margin="0" scrolling="no" src="<?=$url ?>"></iframe>