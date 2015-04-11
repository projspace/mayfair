<?
	$home_banner=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				cms_home_banners
			WHERE
				id = %u
		"
			,$_REQUEST['banner_id']
		)
	);
	$home_banner = $home_banner->FetchRow();
?>