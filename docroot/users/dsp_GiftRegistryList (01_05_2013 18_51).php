<?
	$base_url = $config['dir'].'account/gift-registry/'.$_REQUEST['list_id'].'?';
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
<div id="giftRegister" class="gift-list">
    <h3 class=" capital clear bottom-space">Your list number <?= $gift_list['code'] ?> still has the following gifts available to buy</h3>
    <div class="block">
        <div class="sort">
            <? $url = $params; unset($url['display']); $url = $base_url.implode('&', $url); ?>
            <div class="showing-items" style="margin-right: 190px;">Show: <a class="link clear <?=($display == 'all')?'active':''?>" href="<?=$url ?>&display=all">All items</a>/<a class="link clear <?=($display == 'bought')?'active':''?>" href="<?=$url ?>&display=bought">Bought</a></div>
            <? $url = $params; unset($url['sort']); $url = $base_url.implode('&', $url); ?>
            <div class="filter fl-left">
                Sort by: <a class="link clear <?=($sort_field == 'name')?'active':''?>" href="<?=$url ?>&sort=name&sort_dir=<?=($sort_dir == 'asc')?'desc':'asc' ?>">Name</a>/<a class="link clear <?=($sort_field == 'price')?'active':''?>" href="<?=$url ?>&sort=price&sort_dir=<?=($sort_dir == 'asc')?'desc':'asc' ?>">Price</a>
            </div>
        </div>
        <table class="product-detail available-itmes" style="width: 100%;">
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

                $quantity = array();
                $quantity[] = 'On the list: '.$row['quantity'];
                $quantity[] = 'Bought: '.($row['bought']+0);
                $quantity[] = 'Available: '.($row['quantity'] - $row['bought']);

                echo '
                    <tr>
                        <td class="first"><a href="#">
                            <span class="thumb-frame"><img src="'.$image.'" alt="product" width="121" height="121" /></span></a>
                            <p>'.implode('<br />', $options).'</p>
                        </td>
                        <td><p>'.implode('</p><p>', $quantity).'</p></td>
                        <td>
                            <a href="'.$config['dir'].'account/gift-registry/'.($_REQUEST['list_id']+0).'/quantity/'.$row['id'].'?ajax=1" class="link edit_quantity">Update quantity</a>
                            <a href="'.$config['dir'].'account/gift-registry/'.($_REQUEST['list_id']+0).'/delete-remaining/'.$row['id'].'" class="link">Delete remaining</a>
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
        <a href="#" onclick="javascript: parent.window.location = '<?=$config['dir'] ?>'; return false;" class="btn big-btn green-btn top-space">Browse and add items &gt;</a>
        <div class="clear"></div>
    </div>
</div>
<? $elems->placeholder('script')->captureStart() ?>
<script language="javascript" type="text/javascript">
/* <![CDATA[ */
	function resizeFB(){
		$('#account-gift-registry', parent.document).css('height', ($('#content').height())+'px');
	}

	$(document).ready(resizeFB);
/* ]]> */
</script>
<script language="javascript" type="text/javascript">
/* <![CDATA[ */
	$(document).ready(function(){
		$(".edit_quantity").fancybox({
			'width'				: 530
			,'height'			: '25%'
			,'type'				: 'iframe'
			,'modal'			: false
            ,'padding'			: 0
            ,'overlayColor'		: '#CCCCCC'
            , onStart: function(){
                $('#fancybox-close').css({top: '10px', right: '10px', width: '15px', height: '15px', background: '#CCCCCC url("'+config_dir+'layout/templates/mayfair/css/fancybox/close.gif") no-repeat scroll'});
            }
			,onClosed: function(){
				window.location.reload(true);
			}
		});
	});
/* ]]> */
</script>
<? $elems->placeholder('script')->captureEnd() ?>