<style type="text/css">
	ul.results li.page { display: none; }
    .pagination.page { display: none; }
    #show_products, #show_pages { text-decoration: underline; }
</style>
<div class="search-results">
    <h1>You searched for '<?=$keyword ?>' and this returned <?=$item_count_products ?> <a href="#" id="show_products">products</a> and <?=$item_count_pages ?> <a href="#" id="show_pages">pages</a></h1>

    <ul class="results">
    <?
        while($row = $products->FetchRow())
        {
            if(trim($row['image_type']) != '')
                $image = $config['dir'].'images/product/medium/'.$row['image_id'].'.'.$row['image_type'];
            else
                $image = $config['dir'].'images/product/medium/placeholder.jpg';

            $name = array();
            if(($var = trim($row['code'])) != '')
                $name[] = $var;
            if(($var = trim($row['name'])) != '')
                $name[] = $var;
            $name = implode(' ', $name);

            echo '
                <li class="product">
                    <a class="pic" href="'.product_url($row['id'], $row['guid']).'"><img src="'.$image.'" alt="" width="90"></a>
                    <h2><a href="'.product_url($row['id'], $row['guid']).'">'.htmlentities($name, ENT_NOQUOTES, 'UTF-8').'</a></h2>
                </li>';
        }

        while($row = $pages->FetchRow())
        {
            echo '
                <li class="page">
                    <h2><a href="'.$config['dir'].$row['url'].'">'.htmlentities($row['name'], ENT_NOQUOTES, 'UTF-8').'</a></h2>
                </li>';
        }
    ?>
    </ul>

    <div class="pagination product">
    <?
        $nr_pages = ceil($item_count_products / $items_per_page);
        $max_page_links = 10;

        if($nr_pages > 1)
        {
            $results_page = array();
            $results_page[] = 'keyword='.urlencode($keyword);
            $results_page = $config['dir'].'search?'.implode('&amp;', $results_page).'&amp;page_products=';
            $page = $page_products;

            echo '<div class="sort bottom-space"><div class="pre-next">';

            if($page == 1)
                echo '<a class="link clear" href="#">Previous</a> ';
            else
                echo '<a class="link clear" href="'.$results_page.($page - 1).'">Previous</a> ';

            $links = array();
            for($i = $page - floor($max_page_links/2); $i < $page + ceil($max_page_links/2); $i++)
                if(($i > 0)&&($i <= $nr_pages))
                {
                    if($i == $page)
                        $links[] = '<a href="'.$results_page.$i.'" class="active">'.$i.'</a>';
                    else
                        $links[] = '<a href="'.$results_page.$i.'">'.$i.'</a>';
                }
            echo implode(' / ', $links);

            if($page == $nr_pages)
                echo ' <a href="#" class="link clear">Next</a>';
            else
                echo ' <a href="'.$results_page.($page + 1).'" class="link clear">Next</a>';

            echo '</div></div>';
        }
    ?>
    </div>
    <div class="pagination page">
    <?
        $nr_pages = ceil($item_count_pages / $items_per_page);
        $max_page_links = 10;

        if($nr_pages > 1)
        {
            $results_page = array();
            $results_page[] = 'keyword='.urlencode($keyword);
            $results_page = $config['dir'].'search?'.implode('&amp;', $results_page).'&amp;page_pages=';
            $page = $page_pages;

            echo '<div class="sort bottom-space"><div class="pre-next">';

            if($page == 1)
                echo '<a class="link clear" href="#">Previous</a> ';
            else
                echo '<a class="link clear" href="'.$results_page.($page - 1).'">Previous</a> ';

            $links = array();
            for($i = $page - floor($max_page_links/2); $i < $page + ceil($max_page_links/2); $i++)
                if(($i > 0)&&($i <= $nr_pages))
                {
                    if($i == $page)
                        $links[] = '<a href="'.$results_page.$i.'" class="active">'.$i.'</a>';
                    else
                        $links[] = '<a href="'.$results_page.$i.'">'.$i.'</a>';
                }
            echo implode(' / ', $links);

            if($page == $nr_pages)
                echo ' <a href="#" class="link clear">Next</a>';
            else
                echo ' <a href="'.$results_page.($page + 1).'" class="link clear">Next</a>';

            echo '</div></div>';
        }
    ?>
    </div>
</div>

<? $elems->placeholder('script')->captureStart() ?>
<script language="javascript" type="text/javascript">
/* <![CDATA[ */
	$(document).ready(function(){
        $('#show_products').click(function(){
            $('ul.results li.page').hide();
            $('.pagination.page').hide();

            $('ul.results li.product').show();
            $('.pagination.product').show();
        });

        $('#show_pages').click(function(){
            $('ul.results li.product').hide();
            $('.pagination.product').hide();

            $('ul.results li.page').show();
            $('.pagination.page').show();
        });

	<? if($_REQUEST['page_pages']+0): ?>
		$('#show_pages').click();
    <? else: ?>
        $('#show_products').click();
	<? endif; ?>
	});
/* ]]> */
</script>
<? $elems->placeholder('script')->captureEnd() ?>