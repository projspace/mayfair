<?
	$db->Execute(
		sprintf("
			DELETE FROM
				cms_pages_listings
			WHERE
				id=%u
			AND
				pageid=%u
		"
			,$_POST['listingid']
			,$_POST['pageid']
		)
	);
?>