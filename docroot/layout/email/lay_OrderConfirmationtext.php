<?=strip_tags($content) ?>

Product					Options				Price		Quantity	Subtotal

<?
	foreach($vars['cart'] as $product)
	{
		$options = array();
		if(trim($product['size']) != '')
			$options[] = 'Size: '.$product['size'];
		if(trim($product['width']) != '')
			$options[] = 'Option: '.$product['width'];
		if(trim($product['color']) != '')
			$options[] = 'Color: '.$product['color'];
			
		$product['product_name']."	".implode(', ', $options)."	".price($product['price']-$product['discount'])."	".$product['quantity']."	".price(($product['price']-$product['discount'])*$product['quantity'])."\n";
	}
?>
<? if($vars['params']['vars']['promotional_discount']+0): ?>
Discount Code - <?=$vars['params']['vars']['discount_code'] ?>	<?= price($vars['params']['vars']['promotional_discount']) ?>

<? endif; ?>
<? if($vars['params']['vars']['discount']+0): ?>
Product Discount	<?= price($vars['params']['vars']['discount']) ?>

<? endif; ?>
<? if($vars['params']['vars']['multibuy_discount']+0): ?>
Multi Buy Discount	<?= price($vars['params']['vars']['multibuy_discount']) ?>

<? endif; ?>
<? if($vars['params']['vars']['gift_voucher']+0): ?>
Gift Voucher	<?= price($vars['params']['vars']['gift_voucher']) ?>

<? endif; ?>
Subtotal	<?= price($vars['params']['vars']['total']) ?>

<? if($vars['params']['vars']['packing']+0): ?>
Carrier Bag	<?= price($vars['params']['vars']['packing']) ?>

<? endif; ?>
Shipping	<?= price($vars['params']['vars']['shipping']) ?>

Total		<?= price($vars['params']['vars']['total']+$vars['params']['vars']['shipping']) ?>