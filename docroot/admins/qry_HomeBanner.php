<?
	$home_banner = array();
	
	$result=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				cms_variables
			WHERE
				name = 'home_banner_image_type'
		"
		)
	);
	$home_banner['image_type'] = $result->FetchRow();
	$home_banner['image_type'] = $home_banner['image_type']['value'];
	
	$result=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				cms_variables
			WHERE
				name = 'home_banner_url'
		"
		)
	);
	$home_banner['url'] = $result->FetchRow();
	$home_banner['url'] = $home_banner['url']['value'];
	
	$result=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				cms_variables
			WHERE
				name = 'home_banner_type'
		"
		)
	);
	$home_banner['type'] = $result->FetchRow();
	$home_banner['type'] = $home_banner['type']['value'];
?>