<?
error_reporting(E_ALL);
ini_set('display_errors','1');
set_time_limit(0);
try 
{
	include("../lib/cfg_Config.php");
	include("../lib/adodb/adodb.inc.php");
	include("../lib/act_OpenDB.php");
	include("../lib/lib_Common.php");
	
	$vat=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				shop_variables
			WHERE
				name = 'vat'
		"
		)
	);
	$vat = $vat->FetchRow();
	define('VAT', $vat['value']);

	$block = $db->Execute(
		$sql = sprintf("
			SELECT
				*
			FROM
				shop_deep_linking
			WHERE
				id = %u
		"
			,$_REQUEST['block_id']
	));
	$block = $block->FetchRow();
	if(!$block)
		throw new Exception("Block not found");
		
	if($block['type'] == 'product')
	{
		$products = array();
		$product_ids = array($block['product1_id'], $block['product2_id']);
		$previous_id = 0;
		foreach($product_ids as $product_id)
		{
			$product = $db->Execute(
				$sql = sprintf("
					SELECT
						id
						,name
						,stock
						,imagetype
						,low_stock_trigger
						,IF(vat, price*(100+%f)/100, price) price
					FROM
						shop_products
					WHERE
						id = %u
					AND
						id > 1
					AND
						hidden = 0
					AND
						hide_stock_trigger < stock
					AND
						parent_id = 0
				"
					,VAT
					,$product_id
			));
			$product = $product->FetchRow();
			if(!$product)
			{
				$product=$db->Execute(
					sprintf("
						SELECT
							shop_products.id
							,shop_products.name
							,shop_products.stock
							,shop_products.low_stock_trigger
							,IF(shop_products.vat, shop_products.price*(100+%f)/100, shop_products.price) price
							,shop_products.imagetype
						FROM
						(
							shop_product_similar
							,shop_products
						)
						WHERE
							shop_product_similar.product_id=%u
						AND
							shop_product_similar.similar_product_id = shop_products.id
						AND
							shop_products.hide_stock_trigger < shop_products.stock
						AND
							shop_products.hidden = 0
						AND
							shop_products.parent_id = 0
						ORDER BY 
							RAND()
						LIMIT 1
					"
						,VAT
						,$product_id
					)
				);
				$product = $product->FetchRow();
				if(!$product)
				{
					$product = $db->Execute(
						$sql = sprintf("
							SELECT
								id
								,name
								,stock
								,imagetype
								,low_stock_trigger
								,IF(vat, price*(100+%f)/100, price) price
							FROM
								shop_products
							WHERE
								category_id = %u
							AND
								id > 1
							AND
								hidden = 0
							AND
								parent_id = 0
							AND
								hide_stock_trigger < stock
							AND
								id != %u
							ORDER BY 
								RAND()
							LIMIT 1
						"
							,VAT
							,$block['category_id']
							,$previous_id
					));
					$product = $product->FetchRow();
					if($product)
						$previous_id = $product['id'];
				}
			}
			if($product)
				$products[] = $product;
		}
	}
	else
	{
		$products = $db->Execute(
			$sql = sprintf("
				SELECT
					id
					,name
					,stock
					,imagetype
					,low_stock_trigger
					,IF(vat, price*(100+%f)/100, price) price
				FROM
					shop_products
				WHERE
					category_id IN (%s)
				AND
					id > 1
				AND
					hidden = 0
				AND
					parent_id = 0
				AND
					hide_stock_trigger < stock
				ORDER BY 
					RAND()
				LIMIT 2
			"
				,VAT
				,implode(',', subcategories_ids($block['category_id']))
		));
		$products = $products->GetRows();
	}
	
	$json = array();
	$json['title'] = $block['title'];
	$json['cartUrl'] = $config['protocol'].$config['url'].$config['dir'].'cart';
	$json['addCartUrl'] = $config['protocol'].$config['url'].$config['dir'].'add';
	$json['products'] = array();
	foreach($products as $row)
	{
		if($row['imagetype'])
			$image = $config['protocol'].$config['url'].$config['dir'].'images/product/thumb/'.$row['id'].'.'.$row['imagetype'];
		else
			$image = $config['protocol'].$config['url'].$config['layout_dir'].'images/default-thumb.gif';
			
		$json['products'][] = array('id'=>$row['id'], 'name'=>$row['name'], 'url'=>$config['protocol'].$config['url'].product_url($row['id'],$row['name']), 'image'=>$image, 'price'=>$row['price'], 'buyable'=>($row['stock'] > $row['low_stock_trigger']) );
	}
		
	echo json_encode($json);
}
catch (Exception $e)
{
	echo json_encode(false);
}
?>