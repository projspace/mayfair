<style type="text/css">
	#shop-search { width: 100%; }
	#shop-search label { margin-right: 10px; width: auto;  } 
	
</style>

<div class="content-wrapper" class="yui3-g">
	<form method="post" class="std-form" id="shop-search" action="">
		<input type="hidden" name="act" value="search" />
		<div class="row">
			<label for='zipcode'>Zip Code</label>
			<input type="text" name="zipcode" id="zipcode" value="<?php print $_REQUEST['zipcode']?>" />
			<input type="submit" value="Search" />
		</div>
		<div class="row">
			<label for="distances">Search within</label>
			<label for="5miles" style="">
				<input type="radio" id="5miles" name="distance" value="5" checked /> 5 km
			</label>
			
			<label for="10miles">
				<input type="radio" id="10miles" name="distance" value="10" <?php print ($_REQUEST['distance']+0 == 10)?'checked':'' ?> /> 10 km
			</label>
			
			<label for="15miles">
				<input type="radio" id="15miles" name="distance" value="15"  <?php print ($_REQUEST['distance']+0 == 15)?'checked':'' ?> /> 15 km
			</label>
			
			<label for="20miles">
				<input type="radio" id="20miles" name="distance" value="20"  <?php print ($_REQUEST['distance']+0 == 20)?'checked':'' ?> /> 20 km
			</label>

			<label for="30miles">
				<input type="radio" id="30miles" name="distance" value="30"  <?php print ($_REQUEST['distance']+0 == 30)?'checked':'' ?> /> 30 km
			</label>
			
			<label for="100miles">
				<input type="radio" id="100miles" name="distance" value="100"  <?php print ($_REQUEST['distance']+0 == 100)?'checked':'' ?> /> 100 km
			</label>
			
		</div>
	
	</form>

<br clear="all" />
<?php if(isset($places))
		if(count($places) == 0): ?>
		<h1>No shops found around your location</h1>
<?php else:?>

<aside id="sidebar" class="yui3-u">
	<ul class="pages">
		<li><a href='#' class='on'>Nearest shops:</a></li>
		<?php foreach ( $places as $index => $shop ) :?>
		<li class="shop">
			<a href='#'  rel="<?php print $shop['zip']?>">
				<b><?php print $shop['name']?></b>,<br />
				<?php print $shop['address1']?>, <?php print $shop['address2']?><br />
				<?php print $shop['city']?> <?php print $shop['zip']?>,<br />
				<?php print $shop['phone']?>,<br />
				Distance: <?php print $shop['distance']?> km
			</a>
		</li>
		<?php endforeach;?>
	
	</ul>
</aside>

<div id="content" class="yui3-u" style="width: 730px;">
	<article id="story" class="content-box">
		<div id="map" style="width: 690px; height: 400px; margin: 20px auto;">
		
		</div>
	</article>
</div>

<a href='#' id='reinit'>Reinit map</a>

<script type="text/javascript">

	var map;
	// Init the search address position api
	var geocoder = new google.maps.Geocoder();
	// The start point 
	var startPoint = $('#zipcode').val();

	// Show the directions layer
	var directionsDisplay;
	var directionsService = new google.maps.DirectionsService();
		
	
	function initialize(){
	    directionsDisplay = new google.maps.DirectionsRenderer();
	    
	    var myOptions = {
		      zoom: 11,
		      mapTypeId: google.maps.MapTypeId.ROADMAP
		    };
	    map = new google.maps.Map(document.getElementById("map"),myOptions);

	    directionsDisplay.setMap(map);
	
	    // Show the directions to the first point
	    $('li.shop:eq(0) a').trigger('click');
	}

	
	// Load the directions from startPoint to new place
	function getDirectionsTo ( to ) {
		 
		  var start = startPoint;
		  var end = to;
		  var request = {
		    	origin:start,
		    	destination:end,
		    	travelMode: google.maps.TravelMode.DRIVING
		  };
		  directionsService.route(request, function(result, status) {
		    if (status == google.maps.DirectionsStatus.OK) {
		      directionsDisplay.setDirections(result);
		    }
		  });
				
		
	}

	$('a#reinit').click(function(){
	    initialize();
	    return false;
	});

	$('li.shop a').live('click',function(){
		// add the right on class
	    $('li.shop a').removeClass('on');
	    $(this).addClass('on');

	    //  Show the directions on map
		getDirectionsTo ( $(this).attr('rel') );
		
		return false;
	    
	});

	window.onload = initialize();
	 
</script>

<?php endif;?>

</div>