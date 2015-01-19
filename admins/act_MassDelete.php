<?
	include("../lib/lib_Search.php");
	
	$ok = true;
	foreach((array)$_POST['product'] as $product_id)
	{
		if(!$ok)
			break;
		$_POST['product_id'] = $product_id;
		include("act_RemoveProduct.php");
	}

	if(!$ok)
    	error("There was a problem whilst mass deleting the products, please try again.  If this persists please notify your designated support contact","Database Error");
?>
