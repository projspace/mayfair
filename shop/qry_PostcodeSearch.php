<?
	$keyword = safe($_GET['keyword']);
	
	/* SHOPS */
	$shops = array();
	if($data = get_google_coords($keyword))
	{
		$lat = $data['lat']+0;
		$long = $data['long']+0;
		
		$earth_radius = 3963.0; //miles
		/*$earth_radius = 6378.7; //kilometers
		$earth_radius = 3437.74677; //nautical miles*/
		$radians = 180/M_PI;
		
		$results=$db->Execute(
			sprintf("
				SELECT
					*
				FROM
					shop_variables
				WHERE
					name IN ('postcode_search_distance','postcode_search_results')
			"
			)
		);
		while($row = $results->FetchRow())
			$$row['name'] = $row['value'];
		
		$shops=$db->Execute(
			$sql = sprintf("
				SELECT
					*
					,%1\$f * ACOS(SIN(lat/%2\$f) * SIN(%3\$f/%2\$f) + COS(lat/%2\$f) * COS(%3\$f/%2\$f) * COS(%4\$f/%2\$f - `long`/%2\$f)) distance
				FROM
					shop_user_shops
				WHERE
					%1\$f * ACOS(SIN(lat/%2\$f) * SIN(%3\$f/%2\$f) + COS(lat/%2\$f) * COS(%3\$f/%2\$f) * COS(%4\$f/%2\$f - `long`/%2\$f)) <= %5\$f
				AND
					hidden = 0
				ORDER BY
					distance ASC
				LIMIT %6\$u
			"
				,$earth_radius
				,$radians
				,$lat
				,$long
				,$postcode_search_distance
				,$postcode_search_results
			)
		);
		$shops = $shops->GetRows();
	}
?>