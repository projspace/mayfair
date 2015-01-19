<?
	if(!isset($lat))
	{
		$lat = 38.6788455;
		$long = -97.5897372;
		$zoom = 4;
	}
	else
		$zoom = 10;
?>
<script type="text/javascript" src="<?=$config['protocol'] ?>maps.googleapis.com/maps/api/js?sensor=false"></script>
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
	//google.maps.event.addDomListener(window, 'load', initialize);
	$(window).load(initialize);
</script>

<script language="javascript" type="text/javascript">
/* <![CDATA[ */
	$(window).load(function(){
		var infowindow = new google.maps.InfoWindow({  
			content:  ''
		}); 
		
		$('.shops .shop a').each(function(){
			var link = $(this);
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
		
		$('.shops .shop a').click(function(){
			var latlng = new google.maps.LatLng($(this).attr('lat'), $(this).attr('long'));
			map.setCenter(latlng);
			map.setZoom(16);
			return false;
		});
	});
/* ]]> */
</script>

<div id="content-wrapper" class="yui3-g cwFix">
	<aside id="sidebar" class="yui3-u filters map">
		<? if(!$keyword): ?>
			<h1>Please enter<br />
				your zip code<br />
				to find<br />
				local stores</h1>
		<? endif; ?>
	
		<section id="filters">
			<form method="get" action="<?=$config['dir'] ?>zip-search" id="zipSearch">
				<label for="keyword">
					<span>ZIP Code</span>
				</label>
				<input type="text" id="keyword" name="keyword" value="<?=$keyword ?>" />
				<button type="submit"><em>send</em></button>
			</form>
			
			<!--<div class="filter">
				<ul class="single">
					<li><a data-val="148" href="#"><em></em>5 miles</a></li>
					<li><a data-val="149" href="#"><em></em>10 miles</a></li>
					<li><a data-val="149" href="#"><em></em>15 miles</a></li>
					<li><a data-val="149" href="#"><em></em>25 miles</a></li>
				</ul>
			</div>-->
			
			<ul class="shops">
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
//						if(trim($row['website']) != '')
//							$description[] = trim($row['website']);
//						if(trim($row['email']) != '')
//							$description[] = trim($row['email']);
						echo '
							<li class="shop">
								<h2><a href="#" lat="'.$row['lat'].'" long="'.$row['long'].'">'.htmlentities($row['name'], ENT_NOQUOTES, 'UTF-8').'</a></h2>
								'.implode('<br />', $description).'
							</li>';
					}
				?>
			</ul>
		</section>
	</aside>
	
	<div id="content" class="yui3-u home chFix">
		<article id="map" class="content-box">
			<!--map loads here-->
		</article>
	</div>
</div>