<?
	$area=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				shop_areas
			WHERE 
				id = %u
		"
			,$_REQUEST['area_id']
		)
	);
	$area = $area->FetchRow();
	
	$area['prices']=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				shop_area_prices
			WHERE 
				area_id = %u
			ORDER BY
				weight ASC
		"
			,$area['id']
		)
	);
	$area['prices'] = $area['prices']->GetRows();
?>