<?
	$content_area=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				cms_content_areas
			WHERE 
				id = %u
		"
			,$_REQUEST['area_id']
		)
	);
	$content_area = $content_area->FetchRow();
?>