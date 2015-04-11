<?
	if(!isset($lat))
	{
		$lat = 38.6788455;
		$long = -97.5897372;
		$zoom = 3;
	}
	else
		$zoom = 10;
?>
<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>
<script type="text/javascript">
	var map;
	var marker;
	function initialize() {
		var myOptions = {
			center: new google.maps.LatLng(<?=$lat+0 ?>, <?=$long+0 ?>),
			zoom: <?=$zoom ?>,
			mapTypeId: google.maps.MapTypeId.ROADMAP
		};
		map = new google.maps.Map(document.getElementById("map"), myOptions);
	}
	google.maps.event.addDomListener(window, 'load', initialize);
</script>

<script language="javascript" type="text/javascript">
/* <![CDATA[ */
	$(window).load(function(){
		
		var infowindow = new google.maps.InfoWindow({  
			content:  ''
		}); 
		
		$('.results .shop a').each(function(){
			var link = $(this)
			var latlng = new google.maps.LatLng(link.attr('lat'), link.attr('long'));
			var marker = new google.maps.Marker({
				position: latlng, 
				map: map,
				title: link.text()
			});
			google.maps.event.addListener(marker, 'click', function() {  
				infowindow.setContent( link.html() );
				infowindow.open(map, marker);  
			});
		});
		
		$('.results .shop a').click(function(){
			var latlng = new google.maps.LatLng($(this).attr('lat'), $(this).attr('long'));
			map.setCenter(latlng);
			map.setZoom(16);
			return false;
		});
		
		$('#btnSearch').click(function(){
			$('#cart-form').submit();
			return false;
		});
	});
/* ]]> */
</script>
<style type="text/css">
	ul.results { position: relative; min-height: 400px; }
	ul.results li.shop { padding: 13px; width: 150px; }
	ul.results li.shop a { display: block; }
	ul.results li.shop#map { padding: 5px; position: absolute; top: 0; right: 0; display: none; width: 458px; height: 400px; }
</style>
<div id="content-wrapper" class="yui3-g cwFix">
	<aside id="sidebar" class="yui3-u" style="float:left;">
		<ul class="pages" id="search-results-filter">
			<li><a id="show_shops" class="on" href="#" data-filter='li.shop' data-pagination='p.pagination.shop'>Shops (<?=count($shops) ?>)</a></li>
		</ul>
	</aside>
	<div id="content" class="yui3-u home chFix">
		<article id="search-results" style="float:left; width: 735px;">
			<header class="content-box">
				<? if($keyword): ?>
				<h1>you searched '<?=$keyword ?>'<br /> <?=count($shops) ?> shops</h1>
				<? else: ?>
				<form method="get" action="<?=$config['dir'] ?>zip-search" class="std-form" id="cart-form">
					<ul class="taxes clearfix">
						<li class="yui3-u-1" style="border-bottom: none;">
							<label>Zip code</label>
							<input type="text" class="text" id="keyword" name="keyword" value="<?=$keyword ?>"/>
							<a href="#" class="btn-gray-small" id="btnSearch">Search</a>
						</li>
					</ul>
				</form>
				<? endif; ?>
			</header>
			<div class="content-box">
				<ul class="results">
				<?
					foreach($shops as $row)
					{
						$description = array();
						if(trim($row['address1']) != '')
							$description[] = trim($row['address1']);
						if(trim($row['address2']) != '')
							$description[] = trim($row['address2']);
						if(trim($row['city']) != '')
							$description[] = trim($row['city']);
						if(trim($row['zip']) != '')
							$description[] = trim($row['zip']);
						if(trim($row['phone']) != '')
							$description[] = trim($row['phone']);
						if(trim($row['website']) != '')
							$description[] = trim($row['website']);
						if(trim($row['email']) != '')
							$description[] = trim($row['email']);
						echo '
							<li class="shop">
								<a href="#" lat="'.$row['lat'].'" long="'.$row['long'].'"><h2>'.htmlentities($row['name'], ENT_NOQUOTES, 'UTF-8').'</h2>
								'.implode('<br />', $description).'</a>
							</li>';
					}
					echo '<li class="shop" id="map"></li>';
				?>
				</ul>
			</div>
		</article>
	</div>
</div>