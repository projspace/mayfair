<?
	$supplier=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				shop_suppliers
			WHERE
				id=%u
		"
			,$_REQUEST['supplier_id']
		)
	);
	$supplier = $supplier->FetchRow();
?>