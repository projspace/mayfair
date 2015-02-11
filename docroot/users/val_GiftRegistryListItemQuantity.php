<?
	$validator=new Validation("summary");
	$validator->addRequired("quantity","Quantity");
	$validator->addCustom("quantity","Quantity","checkQuantity","The quantity cannot be lower than the bought items or higher than the available stock.");
		
	function val_checkQuantity($value)
	{
		global $db;
		
		$ret=$db->Execute(
			sprintf("
				SELECT
					SUM(sop.quantity) bought
					,spo.quantity stock
				FROM
					gift_list_items gli
				LEFT JOIN
					shop_order_products sop
				ON
					sop.gift_list_item_id = gli.id
				JOIN
					shop_product_options spo
				ON
					spo.id = gli.option_id
				AND
					spo.product_id = gli.product_id
				WHERE
					gli.id = %u
				GROUP BY
					gli.id
			"
				,$_REQUEST['item_id']
			)
		);
		$ret = $ret->FetchRow();
		return ($value+0 < $ret['bought'] || $ret['stock'] < $value+0)?false:true;
	}
?>