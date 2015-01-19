<?
	$review=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				shop_product_reviews
			WHERE
				id=%u
		"
			,$_REQUEST['review_id']
		)
	);
	$review = $review->FetchRow();
?>