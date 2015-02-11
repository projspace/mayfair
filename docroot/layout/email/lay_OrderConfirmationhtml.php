<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<style type="text/css">
	body { font-family: Arial, Helvetica, sans-serif; font-size: 0.9em; color: #000; margin: 0px; padding: 0px; }
	div#page { width: 564px; }
	h1 { font-size: 1.2em; }
	h2 { font-size: 1em; }
	div#inner { margin-left: 100px; }
	table { width: 100%; font-size: 0.9em; }
</style>
</head>
<body>
	<div id="page">
		<div id="inner">
			<!--<a href="<?=$this->config['protocol'].$this->config['url'].$this->config['dir']?>"><img style="border: none;" src="cid:logo.gif" width="142" height="142" /></a><br />-->
			
			<?=$content ?>

			<table>
				<tr>
					<th align="left">Product</th>
					<th align="left">Options</th>
					<th align="right">Price</th>
					<th align="right">Quantity</th>
					<th align="right">Subtotal</th>
				</tr>
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
						
					echo "<tr>
						<td>".$product['product_code'].":".$product['product_name']."</td>
						<td>".implode(', ', $options)."</td>
						<td align=\"right\">".price($product['price']-$product['discount'])."</td>
						<td align=\"right\">".$product['quantity']."</td>
						<td align=\"right\">".price(($product['price']-$product['discount'])*$product['quantity'])."</td>
					</tr>";
				}
			?>
				<? if($vars['params']['vars']['promotional_discount']+0): ?>
				<tr>
					<td colspan="3" align="right">Discount Code - <?=$vars['params']['vars']['discount_code'] ?></td>
					<td align="right">-<?= price($vars['params']['vars']['promotional_discount']) ?></td>
				</tr>
				<? endif; ?>
				<? if($vars['params']['vars']['discount']+0): ?>
				<tr>
					<td colspan="3" align="right">Product Discount</td>
					<td align="right">-<?= price($vars['params']['vars']['discount']) ?></td>
				</tr>
				<? endif; ?>
				<? if($vars['params']['vars']['multibuy_discount']+0): ?>
				<tr>
					<td colspan="3" align="right">Multi Buy Discount</td>
					<td align="right">-<?= price($vars['params']['vars']['multibuy_discount']) ?></td>
				</tr>
				<? endif; ?>
				<? if($vars['params']['vars']['gift_voucher']+0): ?>
				<tr>
					<td colspan="3" align="right">Gift Voucher</td>
					<td align="right"><?= price($vars['params']['vars']['gift_voucher']) ?></td>
				</tr>
				<? endif; ?>
				<tr>
					<td colspan="3" align="right">Subtotal</td>
					<td align="right"><?= price($vars['params']['vars']['total']) ?></td>
				</tr>
				<? if($vars['params']['vars']['packing']+0): ?>
				<tr>
					<td colspan="3" align="right">Carrier Bag</td>
					<td align="right"><?= price($vars['params']['vars']['packing']) ?></td>
				</tr>
				<? endif; ?>
				<tr>
					<td colspan="3" align="right">Shipping</td>
					<td align="right"><?= price($vars['params']['vars']['shipping']) ?></td>
				</tr>
				<tr>
					<td colspan="3" align="right">Total</td>
					<td align="right"><?= price($vars['params']['vars']['total']+$vars['params']['vars']['shipping']+$vars['params']['vars']['packing']) ?></td>
				</tr>
			</table>
		</div>
	</div>
</body>
