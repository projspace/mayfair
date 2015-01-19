<?
	/**
	 * e-Commerce System
	 * Copyright (c) 2002-2006 Philip John, All Rights Reserved.
	 * Author	: Philip John
	 * Version	: 6.0
	 *
	 * PROPRIETARY/CONFIDENTIAL.  Use is subject to license terms.
	 */
?>
<h1>View Order</h1>


<div id="tabs" class="form clearfix">
	<ul>
		<li><a href="#tabs-1">Order</a></li>
		<? if($txnvars!==false): ?><li><a href="#tabs-2">Transaction</a></li><? endif; ?>
		<li><a href="#tabs-3">Products</a></li>
	</ul>
	<div id="tabs-1">
		<div class="form-field clearfix">
			<label>Time</label>
			<span id="time"><?= date("H:i d/m/Y",$order['time']) ?></span>
		</div>
		<div class="form-field clearfix">
			<label>Billing Name</label>
			<span id="name"><?= $order['name'] ?></span>
		</div>
		<div class="form-field clearfix">
			<label>Billing Address</label>
			<?
				$address=explode("\n",$order['address']);
				$count=0;
				foreach($address as $line)
				{
					if($count>0)
						echo "<label>&nbsp;</label>";
					echo "<span id=\"address{$count}\">{$line}</span><br />";
					$count++;
				}
			?>		
		</div>
		
		<div class="form-field clearfix">
			<label>Billing Zip code</label>
			<span id="postcode"><?= $order['postcode'] ?></span>
		</div>
		
		<div class="form-field clearfix">
			<label>Billing State</label>
			<span id="country"><?= $order['country'] ?></span>
		</div>
		
		<div class="form-field clearfix">
			<label>Delivery Name</label>
			<span id="delivery_name"><?= $order['delivery_name'] ?></span>
		</div>
		
		<div class="form-field clearfix">
			<label>Delivery Address</label>
			<?
				$address=explode("\n",$order['delivery_address']);
				$count=0;
				foreach($address as $line)
				{
					if($count>0)
						echo "<label>&nbsp;</label>";
					echo "<span id=\"delivery_address{$count}\">{$line}</span><br />";
					$count++;
				}
			?>
		</div>
		
		<div class="form-field clearfix">
			<label>Delivery Zip code</label>
			<span id="delivery_postcode"><?= $order['delivery_postcode'] ?></span>
		</div>
		
		<div class="form-field clearfix">
			<label>Delivery State</label>
			<span id="delivery_country"><?= $order['delivery_country'] ?></span>
		</div>
		
		<div class="form-field clearfix">
			<label>Delivery Method</label>
			<span><?= $order['delivery_service_code_name'] ?></span>
		</div>
		
		<div class="form-field clearfix">
			<label>Email</label>
			<span id="email"><a href="mailto:<?= $order['email'] ?>"><?= $order['email']; ?></a></span>
		</div>
		
		<div class="form-field clearfix">
			<label>Tel</label>
			<span id="tel"><?= $order['tel'] ?></span>
		</div>

        <? if($order['gift_payment']): ?>
        <div class="form-field clearfix">
			<label>This is a gift registry order.</label>
		</div>
        <? endif; ?>

        <div class="form-field clearfix">
			<label>Gift Wrap</label>
			<span><?= ($order['packing'] !== null)?'Yes':'No' ?></span>
		</div>

		<div class="form-field clearfix">
			<label>Gift Message</label>
			<span id="gift_message"><?= $order['gift_message'] ?></span>
		</div>

		<? if($pick_up_date = strtotime($order['pick_up_date'])): ?>
		<div class="form-field clearfix">
			<label>Pickup Date</label>
			<span id="pick_up_date"><?=date('d/m/Y H:i', $pick_up_date) ?></span>
		</div>
		<? endif; ?>
		
		<? if($order['refunded'] > 0): ?>
		<div class="form-field clearfix">
			<label>Refund Date</label>
			<span id="refund_date"><?=date('d/m/Y H:i', strtotime($order['refund_date'])) ?></span>
		</div>
		<div class="form-field clearfix">
			<label>Refunded by</label>
			<span id="refund_admin"><?=$order['refund_admin'] ?></span>
		</div>
		<? endif; ?>
	</div>
	<? if($txnvars!==false): ?>
		<div id="tabs-2">
			<?
				$keys=array_keys($txnvars);
				foreach($keys as $key)
				{
					echo "<div class=\"form-field clearfix\"><label for=\"".strtolower(ereg_replace("[^A-Za-z0-9]*","",$key))."\">{$key}</label>
							<span id=\"".strtolower(ereg_replace("[^A-Za-z0-9]*","",$key))."\">{$txnvars[$key]}</span></div>";
				}
			?>
		</div>
	<? endif; ?>
	<div id="tabs-3">
		<table class="values nocheck">
			<tr>
				<th>&nbsp;</th>
				<th>Product</th>
				<th>Options</th>
				<th class="right">Price</th>
				<th class="right">Qty.</th>
				<th class="right">Total</th>
			</tr>
			<?
				$pkeys=$products->GetKeys();
				$p=0;
				while($row=$products->FetchRow())
				{
					$p++;
					if($class=="light")
					{
						$class="dark";
						$linkclass="white";
					}
					else
					{
						$class="light";
						$linkclass="a";
					}
					
					$options = array();
                    if(trim($row['upc_code']) != '')
						$options[] = 'SKU / '.$row['upc_code'];
					if(trim($row['size']) != '')
						$options[] = 'Size / '.$row['size'];
					if(trim($row['width']) != '')
						$options[] = 'Option / '.$row['width'];
					if(trim($row['color']) != '')
						$options[] = 'Color / '.$row['color'];

					if($order['promotional_discount_type'] == 'percent')
					{
						if($row['promotional_discount']+0)
							$price = '<span style="text-decoration: line-through;">'.price($row['order_price']*$row['order_quantity']).'</span><br />after discount<br />'.price($row['order_price']*$row['order_quantity'] - $row['promotional_discount']);
						else
							$price = ''.price($row['order_price']*$row['order_quantity']).'<br />no discount';
					}
					else
						$price = ''.price($row['order_price']*$row['order_quantity']);
						
					echo "<tr>
							<td class=\"$class\">{$p}</td>
							<td class=\"$class\">{$row['name']}</td>
							<td class=\"$class\">".implode('<br />', $options)."</td>
							<td class=\"$class right\">".price($row['order_price'])."</td>
							<td class=\"$class right\">{$row['order_quantity']}</td>
							<td class=\"$class right\">".$price."</td>
						</tr>";

					if($row['order_custom']!="")
					{
						$custom=unserialize($row['order_custom']);
						
						echo "<tr>
							<td colspan=\"6\"><h4>Delivery Details</h4>
							<p><em>Please note, if there are less delivery details than product quantity, surpluss to go to main address at top of page</em></p>";
						foreach($custom['delivery'] as $item)
						{
							if(checkdelivery($item))
							{
								echo "<label>Name</label>
										<span>".$item['name']."</span><br />
									<label>Address</label>
										<span>".str_replace(" ","<br />",$item['address'])."</span><br />
									<label>Country</label>
										<span>".$item['country']."</span><br />
									<label>Message</label>
										<span>".$item['message']."</span><br />
									<label>Instructions</label>
										<span>".$item['instructions']."</span><br />";	
							}
						}
						if($details!="")
						{
							echo "<tr>
								<td colspan=\"6\"><h4>Delivery Details</h4>
								<p><em>Please note, if there are less delivery details than product quantity, surpluss to go to main address at top of page</em></p>";
							echo $details;
							echo "</td>
								</tr>";
						}
					}		
				}
			?>
			<? if($order['promotional_discount']+0): ?>
			<tr>
				<td colspan="5" class="right"><strong>Discount Code - <?=$order['discount_code'] ?></strong></td>
				<th class="right">-<?= price($order['promotional_discount']) ?></td>
			</tr>
			<? endif; ?>
			<? if($order['discount']+0): ?>
			<tr>
				<td colspan="5" class="right"><strong>Product Discount</strong></td>
				<th class="right">-<?= price($order['discount']) ?></td>
			</tr>
			<? endif; ?>
			<? if($order['multibuy_discount']+0): ?>
			<tr>
				<td colspan="5" class="right"><strong>Multi Buy Discount</strong></td>
				<th class="right">-<?= price($order['multibuy_discount']) ?></td>
			</tr>
			<? endif; ?>
			<? if($order['gift_voucher']+0): ?>
			<tr>
				<td colspan="5" class="right"><strong>Gift Voucher</strong></td>
				<th class="right"><?= price($order['gift_voucher']) ?></td>
			</tr>
			<? endif; ?>
			<tr>
				<td colspan="5" class="right"><strong>Subtotal</strong></td>
				<th class="right"><?= price($order['total']) ?></td>
			</tr>
			<? if($order['packing']+0): ?>
			<tr>
				<td colspan="5" class="right"><strong>Carrier Bag</strong></td>
				<th class="right"><?= price($order['packing']) ?></td>
			</tr>
			<? endif; ?>
			<tr>
				<td colspan="5" class="right"><strong>Shipping</strong></td>
				<th class="right"><?= price($order['shipping']) ?></td>
			</tr>
			<tr>
				<td colspan="5" class="right"><strong>Tax</strong></td>
				<th class="right"><?= price($order['tax']) ?></td>
			</tr>
			<tr>
				<td colspan="5" class="right"><strong>Total</strong></td>
				<th class="right"><?= price($order['total']+$order['shipping']+$order['packing']+$order['tax']) ?></td>
			</tr>
			<tr>
				<td colspan="5" class="right"><strong>Paid</strong></td>
				<th class="right"><?= price($order['paid']) ?></th>
			</tr>
			<? if($order['refunded'] > 0): ?>
			<tr>
				<td colspan="5" class="right"><strong>Refunded</strong></td>
				<th class="right"><?= price($order['refunded']) ?></th>
			</tr>
			<? endif; ?>
		</table>
	</div>
</div>

<span class="button button-grey right" style="margin-left: 10px"><input type="button" onclick="javascript:window.location='<?= $config['dir'] ?>index.php?fuseaction=admin.orders';" value="Back" /></span>
<? if($acl->check("refundOrder") && $order['refunded'] != $order['paid']): ?>
	<form method="post" onsubmit="return formConfAct(this,'index.php','cancel','order');" class="float-right" style="margin-left:5px;">
		<input type="hidden" name="fuseaction" value="admin.refundOrder" />
		<input type="hidden" name="order_id" value="<?=$order['id'] ?>" />
		<span class="button button-grey right"><input type="submit" class="submit" value="Cancel Order" /></span>
	</form>
<? endif; ?>
<? if($acl->check("processOrder")): ?>
	<form method="post" onsubmit="return formConfAct(this,'index.php','process','order');" class="float-right" style="margin-left:5px;">
		<input type="hidden" name="fuseaction" value="admin.processOrder" />
		<input type="hidden" name="order_id" value="<?=$order['id'] ?>" />
		<span class="button button-grey right"><input type="submit" class="submit" value="Process Order" /></span>
	</form>
<? endif; ?>
<span class="button button-small right" style="margin-bottom:5px; margin-left: 10px;"><input type="button" onclick="javascript:window.open('<?= $config['dir'] ?>admins/shipping_note_order.php?order_id=<?=$order['id'] ?>','Shipping','directories=no,height=480,width=640,location=no,menubar=no,resizable=yes,scrollbars=no,status=no,toolbar=no');" value="Packing List" /></span>
<span class="button button-small right" style="margin-bottom:5px"><input type="button" onclick="javascript:window.open('<?= $config['dir'] ?>admins/invoice.php?order_id=<?=$order['id'] ?>','Invoice','directories=no,height=480,width=640,location=no,menubar=no,resizable=yes,scrollbars=no,status=no,toolbar=no');" value="Generate Invoice" /></span>