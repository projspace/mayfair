<?
	$page = $_REQUEST['page']+0;
	if($page <= 0)
		$page = 1;
	$items_per_page = 10;
	
	$item_count=$db->Execute(
		sprintf("
			SELECT
				COUNT(*) count
			FROM
				shop_newsletter_emails 
		"
		)
	);
	$item_count = $item_count->FetchRow();
	$item_count = $item_count['count'];
	
	$newsletter_emails=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				shop_newsletter_emails
			ORDER BY
				email ASC
			LIMIT
				%u, %u
		"
			,($page - 1)*$items_per_page
			,$items_per_page
		)
	);
?>