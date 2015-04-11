<?
	$result=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				shop_areas
			ORDER BY
				name ASC
		"
		)
	);
	$areas = array();
	while($row = $result->FetchRow())
	{
		$countries=$db->Execute(
			sprintf("
				SELECT
					id
					,name
				FROM
					shop_countries
				WHERE
					area_id = %u
				ORDER BY
					name ASC
			"
				,$row['id']
			)
		);
		$row['countries'] = array();
		while($country = $countries->FetchRow())
			$row['countries'][$country['id']] = $country['name'];
		$areas[] = $row;
	}
?>