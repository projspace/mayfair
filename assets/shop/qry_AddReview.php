<?
	$product_id = $_REQUEST['product_id']+0;
	
	$product=$db->Execute(
		sprintf("
			SELECT
				shop_products.*
				,IF(shop_products.vat, shop_products.price*(100+%f)/100, shop_products.price) price
			FROM
				shop_products
			WHERE
				shop_products.id=%u
			AND
				shop_products.id > 1
		"
			,VAT
			,$product_id
		)
	);
	$product = $product->FetchRow();
?>
