<?php 

	$places = array();
	foreach($shops as $index=>$shop) {
		
		$postcode[$index] = str_replace(" ","+",$shop['zip']);
		$places[$index] = $shop;
		
	}
	
	$query = "http://maps.googleapis.com/maps/api/distancematrix/json?mode=driving&sensor=false&origins=".$_REQUEST['zipcode']."&destinations=";
	$query .= implode("|",$postcode);
	
	if($google_response = json_decode(@file_get_contents($query))) {
		
		
		if($google_response->status == 'OK') {
			foreach($google_response->rows[0]->elements as $index=>$object) {
				if( (float) $object->distance->text <= (float)$_REQUEST['distance'])
					$places[$index]['distance'] = (float) $object->distance->text;
				else
					unset($places[$index]); 
			}
		}
		
	} 
	