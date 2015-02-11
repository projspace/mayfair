<?
	$base_url = $config['dir'].'gift-registry/list/'.$gift_list['code'].'?';
	$params = array();
	if(isset($_REQUEST['display']))
		$params['display'] = 'display='.urlencode($_REQUEST['display']);
	if(isset($_REQUEST['sort']))
		$params['sort'] = 'sort='.urlencode($_REQUEST['sort']);
	if(isset($_REQUEST['sort_dir']))
		$params['sort_dir'] = 'sort_dir='.urlencode($_REQUEST['sort_dir']);
	if(isset($_REQUEST['page']))
		$params['page'] = 'page='.urlencode($_REQUEST['page']);
?>
<div class="banner banner-small">
    <div class="banner-info"> <img src="<?=$config['layout_dir'] ?>images/banner3.jpg" alt="banner" />
        <div class="banner-content billing-img">
            <h2 class="golden">Gift List</h2>
            <p>Nobiscienda siminve rnatemo luptatemod eos cum alique reris voluptatus. Ibus. Il es ut laccusanti as entem sent mod qui doluptati assi cum et, cum Us accae pligniet alignih ilibus sendita ecesti con cupta et res am rerro dent re explab ium res sequatempora net pratus et in repelis dolore plitist oreped ulparunt in ent, aut fugita nihicia spicien emquostia sitat liquis prepratquide eum sum</p>
        </div>
    </div>
</div>
<div class="block">
    <div class="tab-wrapper">
        <ul class="tab-nav">
            <li class="active"><a href="#" >GIFTS LIST</a></li>
        </ul>
        <div class="tab-content gift-list">
            <h3 class=" capital clear bottom-space">the following items on the list <?= $gift_list['code'] ?> are still available to buy:</h3>
            <div class="block">
                <div class="sort">
                    <? $url = $params; unset($url['display']); $url = $base_url.implode('&', $url); ?>
                    <div class="showing-items">Show: <a class="link clear <?=($display == 'all')?'active':''?>" href="<?=$url ?>&display=all">All items</a>/<a class="link clear <?=($display == 'bought')?'active':''?>" href="<?=$url ?>&display=bought">Bought</a></div>
                    <? $url = $params; unset($url['sort']); $url = $base_url.implode('&', $url); ?>
                    <div class="filter fl-left">
                        Sort by: <a class="link clear <?=($sort_field == 'name')?'active':''?>" href="<?=$url ?>&sort=name&sort_dir=<?=($sort_dir == 'asc')?'desc':'asc' ?>">Name</a>/<a class="link clear <?=($sort_field == 'price')?'active':''?>" href="<?=$url ?>&sort=price&sort_dir=<?=($sort_dir == 'asc')?'desc':'asc' ?>">Price</a>
                    </div>
                </div>
                <table class="product-detail available-itmes">
                    <tr class=" heading">
                        <td class="first">Product details</td>
                        <td>QUANTITY</td>
                        <td>OPTIONS</td>
                    </tr>
                <?
                    while($row = $products->FetchRow())
                    {
                        if($row['image_type'])
                            $image = $config['dir'].'images/product/medium/'.$row['image_id'].'.'.$row['image_type'];
                        else
                            $image = $config['dir'].'images/product/medium/placeholder.jpg';

                        $options = array();
                        $options[] = $row['name'];
                        if(trim($row['code']) != '')
                            $options[] = $row['code'];
                        $options[] = 'Price: '.price($row['price']);
                        if(trim($row['size']) != '')
                            $options[] = 'Size / '.$row['size'];
                        if(trim($row['width']) != '')
                            $options[] = 'Width / '.$row['width'];
                        if(trim($row['color']) != '')
                            $options[] = 'Colour / '.$row['color'];

                        $qty_options = '';
                        for($i=(($row['bought']+0)?$row['bought']+0:1);$i<=min($row['stock']+0, $row['quantity'] - $row['bought']);$i++)
                            $qty_options .= '<option value="'.$i.'">'.$i.'</option>';

                        echo '
                            <tr>
                                <td class="first"><a href="#">
                                    <span class="thumb-frame"><img src="'.$image.'" alt="product" width="121" height="121" /></span></a>
                                    <p>'.implode('<br />', $options).'</p>
                                </td>
                                <td><p>'.($row['bought']+0).' of '.($row['quantity']+0).'</p></td>
                                <td>
                                    '.(($row['bought']+0 < $row['quantity']+0)?'
                                    <form action="#" method="post" class="frmGift">
                                        <input type="hidden" name="product_id" value="'.$row['product_id'].'"/>
                                        <input type="hidden" name="option_id" value="'.$row['option_id'].'"/>
                                        <input type="hidden" name="gift_list_item_id" value="'.$row['id'].'"/>
                                        <div class="report-box  custom-select text-big small-select">
                                            <select name="quantity" class="quantity styled" >'.$qty_options.'</select>
                                        </div>
                                        <a href="#" class="link fl-right submit">add to cart</a>
                                    </form>
                                    ':'').'
                                </td>
                            </tr>
                        ';
                    }
                ?>
                </table>
                <?
                    $nr_pages = ceil($item_count / $items_per_page);
                    $max_page_links = 10;

                    if($nr_pages > 1)
                    {
                        echo '<div class="sort bottom-space"><div class="pre-next">';

                            $results_page = $params;
                            unset($results_page['page']);
                            $results_page = $base_url.implode('&', $results_page).'&page=';

                            if($page == 1)
                                echo '<a class="link clear" href="#" style="display: none;">Previous</a>';
                            else
                                echo '<a class="link clear" href="'.$results_page.($page - 1).'">Previous</a>';

                            for($i = $page - floor($max_page_links/2); $i < $page + ceil($max_page_links/2); $i++)
                            {
                                if($i > $page - floor($max_page_links/2))
                                    echo '/';
                                if(($i > 0)&&($i <= $nr_pages))
                                {
                                    if($i == $page)
                                        echo ' <a href="'.$results_page.$i.'" class="active">'.$i.' </a>';
                                    else
                                        echo ' <a href="'.$results_page.$i.'">'.$i.' </a>';
                                }
                            }

                            if($page == $nr_pages)
                                echo ' <a class="link clear" href="#" style="display: none;">Next</a>';
                            else
                                echo ' <a class="link clear" href="'.$results_page.($page + 1).'">Next</a>';

                        echo '</div></div>';
                    }
                ?>
                <a href="<?=$config['dir'] ?>" class="btn big-btn green-btn top-space">Browse and add items &gt;</a>
            </div>
        </div>
    </div>
</div>
<? $elems->placeholder('script')->captureStart() ?>
<script language="javascript" type="text/javascript">
/* <![CDATA[ */
	$(document).ready(function(){
		$('.frmGift a.submit').unbind('click').click(function(){
			var $p = $('<p class="buttons yui3-u-1 message">Adding to basket</p>');
			$(this).parent().hide().before($p);
			var data = $(this).closest('.frmGift').serialize();
			$.ajax({
				url: '<?=$config['dir'] ?>ajax/act_AddCart.php',
				type: 'post',
				dataType: 'json',
				data: data,
				success: function(json){
					if(json.status)
						$p.html('Product added to basket.').addClass('success');
					else
						$p.html(json.message).addClass('failed');

					window.scrollTo(0,0);
					$.cart.reload();
					$.cart.show();
					$.cart.display('#cart .contents li:last', '-');
				}
			});
			return false;
		});
	});
/* ]]> */
</script>
<? $elems->placeholder('script')->captureEnd() ?>