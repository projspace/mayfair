<?
	$brand=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				shop_brands
			WHERE
				id=%u
		"
			,$_REQUEST['brand_id']
		)
	);
	$brand = $brand->FetchRow();
?>