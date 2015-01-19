<style type="text/css">
    .product-list h2 { position: static; }
    .product-list li { height: auto; }
    .product-list li img { margin: 5px 0; }
    .product-list li .btn-box { position: static; margin: 0; text-align: center;}
    .product-list li .btn-box .btn { float: none; display: inline-block; }
</style>
<div id="product-page">
    <? if($category && $category['content_visible']): ?>
    <div class="banner banner-small">
        <div class="banner-info">
            <? if(trim($category['imagetype'])): ?><img alt="banner" src="<?=$config['dir'] ?>images/category/<?=$category['id'] ?>.<?=$category['imagetype'] ?>" width="373"/><? endif; ?>
            <? if(trim($category['content_image'])): ?><img alt="banner" src="<?=$category['content_image'] ?>" width="373"/><? endif; ?>
            <div class="banner-content JEWELRY" style="float: right; margin-right: 10px; margin-left: 0; width: 830px;">
                <h2><?= $category['name'] ?></h2>
                <?= $category['content'] ?>
            </div>
        </div>
    </div>
    <? endif; ?>
    <div class="block <?=($category && $category['content_visible'])?'':'top-space' ?>">
        <!-- Sidebar -->
        <div class="sidebar sub-sidebar">
            <h4 class="overflow">REFINE BY:<!-- <a href="#" class="link">Clear all</a>--></h4>
            <div id="filters">
            </div>
            <div id="sidebar_subcats">
    		</div>
            <!-- adds -->
            <!--<div class="block add-block-img"> <a href="#"><img src="<?=$config['layout_dir'] ?>images/add-car.jpg" alt="adds" /></a> </div>-->
            <!-- End adds -->
        </div>

        <!-- End Sidebar -->
        <div class="right-container">
            <div class="sort">
                <div class="showing-items">Showing items 1 - 20 of 80</div>
                <div class="pre-next"><a href="#" class="link clear prev" style="display: inline-block;">Previous</a> <div class="clear p" style="float: right; display: inline-block;"></div> <a href="#" class="link clear next" style="display: inline-block;">Next</a></div>
                <div class="view-all">View <a href="#" data-items="12" class="active">12</a> <a href="#" data-items="24">24</a> <a href="#" data-items="all" class="link clear ins">View all</a></div>
                <div class="sortby" style="float: right;">Sort by: <a href="#" class="link clear" data-sort="name">Name</a>/<a href="#" class="link clear" data-sort="price_asc">Price</a></div>
            </div>
            
            <div class="block product-list">
                <ul>
                </ul>
            </div>

						<p class="totop"><a href="#header">Back to top &uarr;</a></p>

            <div class="sort">
                <div class="showing-items">Showing items 1 - 20 of 80</div>
                <div class="pre-next"><a href="#" class="link clear prev" style="display: inline-block;">Previous</a> <div class="clear p" style="float: right; display: inline-block;"></div> <a href="#" class="link clear next" style="display: inline-block;">Next</a></div>
                <div class="view-all">View <a href="#" data-items="12" class="active">12</a> <a href="#" data-items="24">24</a> <a href="#" data-items="all" class="link clear ins">View all</a></div>
                <div class="sortby" style="float: right;">Sort by: <a href="#" class="link clear" data-sort="name">Name</a>/<a href="#" class="link clear" data-sort="price_asc">Price</a></div>
            </div>
        </div>
        <!-- content -->

        <!-- End Content -->

    </div>
</div>
<? $elems->placeholder('script')->captureStart() ?>
<script type="text/javascript" src="<?=$config['layout_dir'] ?>js/jquery.custom_radio_checkbox.js"></script>
<script language="javascript" type="text/javascript">
$(document).ready(function(){
	$(".radio").dgStyle();
	$(".checkbox").dgStyle();
});
</script>
<script type="text/javascript">
	$(document).ready(function(){
		var $filters = $("#filters");
		var $sidebar_subcats = $("#sidebar_subcats");
		var $pageview = $(".view-all");
		var $sort = $(".sortby");
		var $paging = $(".pre-next");
		var $prods = $(".right-container .product-list ul");
		
		var reInit = function(data){
			if(data !== undefined){
				if(data.sidebar !== undefined)
				{
					$filters.hide();
					$sidebar_subcats.html(data.sidebar).show();
				}
				else
				{
					$filters.show();
					$sidebar_subcats.hide();
					$filters.ws_filters(data.filters).trigger('ajax-stop');
				}
				$("#view-options").trigger('ajax-stop');

				$(".checkbox").customInput();
				$(".checkbox").dgStyle();

				$sort.show().find('a[data-sort="'+data.sortby.selected+'"]').addClass('active').siblings('a').removeClass('active');

				if ( typeof data.products == 'string' && data.products.length ){
					$prods.html(data.products);
					$prods.find('li:nth-child(3n+1)').each(function(){
						var max = [];
						max[max.length] = $('h2', $(this)).height();
						max[max.length] = $('h2', $(this).next()).height();
						max[max.length] = $('h2', $(this).next().next()).height();
						max = Math.max(max[0], max[1], max[2]);

						$('h2', $(this)).css('height', max+'px');
						$('h2', $(this).next()).css('height', max+'px');
						$('h2', $(this).next().next()).css('height', max+'px');
					});
					$prods.trigger('ajax-stop');
				}

				$paging.pagination(data.paging);
				$paging.trigger('ajax-stop');
			}
		};
		var loadProducts = function(initData){
			initData = initData || {};
			$("#view-options").trigger('ajax-start');
			$paging.trigger('ajax-start');
			$prods.trigger('ajax-start');
			var data = {
				filters: initData.filters || $filters.trigger('ajax-start').ws_filters('getdata'),
				sortby: $sort.find("a.active").data('sort'),
				pageview: $pageview.find("a.active").data('items'),
				page: initData.page || $paging.find("a.active").data('page'),
				category_id: initData.category_id || null,
				brand_id: initData.brand_id || null
				<? if($_REQUEST['type']): ?>, category_type: '<?=$_REQUEST['type'] ?>'<? endif; ?>
			};
			
			//window.location.hash = '#product_load=' + encodeURIComponent(JSON.stringify(data));
			
			$.ajax({
				url: '<?=$config['dir'] ?>ajax/qry_Category.php',
				type: 'post',
				data: data,
				dataType: 'json',
				success: reInit
			});
			
			return false;
		};

		var pageclick = function(){
			if ( $(this).parents('.loading').length ) {
				return false;
			}
			
			var page = $(this).data('page');
			if(typeof(page) !== 'undefined') {
				$paging.find('a').removeClass('active').filter('[data-page="' + page + '"]').addClass('active');
			}
			loadProducts();
			
			return false;
		};
        var sortclick = pageclick;

		$filters.bind('changed.ws-filters', loadProducts);
		
		$paging.find('.p a').live('click', pageclick);
		$paging.find('a.next, a.prev').click(function(){
			if ( $(this).parents('.loading').length ) return false;
			var page = $paging.find('a.active').data('page');
			if(typeof(page) !== 'undefined') {
				if ( $(this).is('.next') ) {
					++page;
				} else {
				--page;
				}
				$paging.find('a').removeClass('active').filter('[data-page="' + page + '"]').addClass('active');
			}
			loadProducts();
			return false;
		});
		
		$pageview.find('a').click(function() {
			if ( $(this).parents('.loading').length ) {
				return false;
			}
			
			var items = $(this).data('items');
			if(typeof(items) !== 'undefined') {
				$pageview.find('a').removeClass('active').filter('[data-items="' + items + '"]').addClass('active');
			}
			loadProducts();
			
			return false;
		});
		
		$sort.find('a').click(function() {
			if ( $(this).parents('.loading').length ) {
				return false;
			}
			
			var sort = $(this).data('sort');
			if(typeof(sort) !== 'undefined') {
				$sort.find('a').removeClass('active').filter('[data-sort="' + sort + '"]').addClass('active');
			}
			loadProducts();
			
			return false;
		});
		
		var hashParameters = {};
		var segments = window.location.hash.substr(1).split('&');
		for(var i = 0, c = segments.length; i < c; ++i) {
			var parts = segments[i].split('=');
			var name = parts.shift();
			hashParameters[name] = parts.join('=');
		}
		var initData = {
			category_id: <?=$_REQUEST['category_id']+0 ?>,
			brand_id: <?=$_REQUEST['brand_id']+0 ?>
		};
		
		if(hashParameters.product_load) {
			try {
				var productData = JSON.parse(decodeURIComponent(hashParameters.product_load));
			
				if(typeof(productData.page) !== 'undefined') {
					$paging.find('a').removeClass('active').filter('[data-page="' + productData.page + '"]').addClass('active');
				}
			
				if(typeof(productData.pageview) !== 'undefined') {
					$('.view-all a').removeClass('active').filter('[data-items="' + productData.pageview + '"]').addClass('active');
				}			
				
				if(typeof(productData.sortby) !== 'undefined') {
					$('.sortby a').removeClass('active').filter('[data-sort="' + productData.sortby + '"]').addClass('active');
				}
				
				if(typeof(productData.filters) !== 'undefined') {
					initData.filters = productData.filters;
				}
				
				if(typeof(productData.page) !== 'undefined') {
					initData.page = productData.page;
				}
			} catch(e) {
				//I'm taking my secrets to my grave.
				//console.log(e);
			}			
		}
		
		loadProducts(initData);		
	});
</script>
<? $elems->placeholder('script')->captureEnd() ?>