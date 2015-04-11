<?
	if(!$user_session->check())
	{
		header("location: ".$config['dir'].'login?return_url='.urlencode($config['dir'].'wishlist/add/'.$_REQUEST['cart_id']));
		exit;
	}
	$cart_id = $_REQUEST['cart_id']+0;
	
	$source=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				shop_session_cart
			WHERE
				id=%u
		"
			,$cart_id
		)
	);
	$source = $source->FetchRow();
	if(!$source)
		return;
		
	$destination=$db->Execute(
		sprintf("
			SELECT
				id
			FROM
				shop_wishlist
			WHERE
				product_id=%u
			AND
				option_id=%u
			AND
				user_id=%u
		"
			,$source['product_id']
			,$source['option_id']
			,$user_session->account_id
		)
	);
	$destination = $destination->FetchRow();
	
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();
	
	if($destination)
	{
		$db->Execute(
			sprintf("
				UPDATE
					shop_wishlist
				SET
					quantity=quantity+%u
				WHERE
					id=%u
			"
				,$source['quantity']
				,$destination['id']
			)
		);
	}
	else
	{
		$db->Execute(
			sprintf("
				INSERT INTO	shop_wishlist (
					user_id
					,product_id
					,option_id
					,quantity
				) VALUES (
					%u
					,%u
					,%u
					,%u
				)
			"
				,$user_session->account_id
				,$source['product_id']
				,$source['option_id']
				,$source['quantity']
			)
		);
		$wish_id=$db->Insert_ID();
	}
	
	$db->Execute(
		sprintf("
			DELETE FROM
				shop_session_cart
			WHERE
				id=%u
		"
			,$cart_id
		)
	);
	
	$ok=$db->CompleteTrans();
?>
