<script type="text/javascript">
/* <![CDATA[ */
	$(document).ready(function(){
		var x = $('body').height()+30;
		var y = $('body').width()+40;
		parent.$.colorbox.resize({width:y, height:x});
	});
/* ]]> */
</script>

<form id="frmOrders" action="#" method="post" class="reviewForm" style="width: 500px;">
	<h3>Order Details</h3>
	
	<table class="basket noBBord">
		<thead>
			<tr>
				<th colspan="2">Item</th>
				<th>Quantity</th>
				<th class="right">Price</th>
			</tr>
		</thead>
		<tbody>
		<?
			foreach($products as $index=>$row)
			{
				$class = '';
				if($index == 0)
					$class = 'class="firstRow"';
				if($index == count($rows)-1)
					$class = 'class="lastRow bottomBordered"';
					
				$options = '';
				if(is_array($row['options']))
				{
					$options .= '<br />';
					foreach($row['options'] as $option_index=>$option)
						$options .= '<strong>'.$option['name'].'</strong>: '.$option['value'][$row['cart_options'][$option_index]].'<br />';
				}
				
				if($row['imagetype'])
					$image = $config['dir'].'images/product/thumb/'.$row['id'].'.'.$row['imagetype'];
				else
					$image = $config['layout_dir'].'images/default-thumb.gif';
					
				echo '
					<tr '.$class.'>
						<td class="imagePreview"><a href="#"><img src="'.$image.'" width="71" height="69" alt="*"/></a></td>
						<td><a href="#"><em>'.htmlentities($row['name'], ENT_NOQUOTES, 'UTF-8').'</em>'.ucwords($row['tags']).'</a><br />'.$options.'</td>
						<td class="cleanQ">'.$row['cart_quantity'].'</td>
						<td class="right"><strong>'.price($row['cart_price']).'</strong></td>
					</tr>';
			}
		?>
		</tbody>
		<?
			$subtotal = array();
			if($order['promotional_discount'])
				$subtotal[] = array(
					'label' => 'Discount Code '.$order['discount_code']
					,'price' => price($order['promotional_discount']*(-1))
					,'class' => ''
				);
			if($order['discount'])
				$subtotal[] = array(
					'label' => 'Product Discount'
					,'price' => price($order['discount']*(-1))
					,'class' => ''
				);
			if($order['multibuy_discount'])
				$subtotal[] = array(
					'label' => 'Multi Buy Discount'
					,'price' => price($order['multibuy_discount']*(-1))
					,'class' => ''
				);
			$subtotal[] = array(
				'label' => '<strong>SubTotal</strong>'
				,'price' => '<strong>'.price($order['total']).'</strong>'
				,'class' => 'subtotal'
			);
			$subtotal[] = array(
				'label' => 'Postage & Packing'
				,'price' => price($order['packing']+$order['shipping'])
				,'class' => ''
			);
			$subtotal[] = array(
				'label' => 'Final Total'
				,'price' => price($order['total']+$order['packing']+$order['shipping'])
				,'class' => 'finalTotal'
			);
		?>
		<tfoot class="review">
			<tr>
				<td colspan="2" rowspan="<?=count($subtotal)+2 ?>" class="deliveryAddress">
					<br/>
					<strong>Delivery address</strong><br/>
					<address><?=htmlentities($order['delivery_name'], ENT_NOQUOTES, 'UTF-8') ?></address>
					<address><?=htmlentities($order['delivery_address'], ENT_NOQUOTES, 'UTF-8') ?></address>
					<address><?=htmlentities($order['delivery_postcode'], ENT_NOQUOTES, 'UTF-8') ?></address>
					<address><?=htmlentities($order['delivery_country'], ENT_NOQUOTES, 'UTF-8') ?></address>
				</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<?
				foreach($subtotal as $row)
					echo '
						<tr class="'.$row['class'].'">
							<td>'.$row['label'].'</td>
							<td class="right">'.$row['price'].'</td>
						</tr>';
			?>
			<tr>
				<td colspan="2" class="right">
					&nbsp;
				</td>
			</tr>
		</tfoot>
	</table>
</form>
