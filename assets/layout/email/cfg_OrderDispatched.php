<?
	global $db;

	$cms_email = $db->Execute(
		sprintf("
			SELECT
				*
			FROM 
				cms_emails
			WHERE
				id = %u
		"
			,4
		)
	);
	$cms_email = $cms_email->FetchRow();
	$variables = unserialize($cms_email['variables']);
	
	$tmp = array();
	$tmp['[shipping_number]'] = $vars['shipping_number'];
	$tmp['[order_number]'] = $this->config['companyshort'].$vars['order']['id'];
	$tmp['[order_date]'] = date('m/d/Y', $vars['order']['time']);
	$tmp['[customer]'] = $vars['order']['delivery_name'];
	$tmp['[address]'] = nl2br($vars['order']['delivery_address']).", ".$vars['order']['delivery_country']."<br />\n".$vars['order']['delivery_postcode'];
	
	$tmp['[products]'] = '<table>
		<tr>
			<th>Product</th>
			<th>Options</th>
			<th align="right">Price</th>
			<th align="right">Quantity</th>
			<th align="right">Subtotal</th>
		</tr>';
	foreach($vars['products'] as $product)
	{
		$options = array();
		if(trim($product['size']) != '')
			$options[] = 'Size: '.$product['size'];
		if(trim($product['width']) != '')
			$options[] = 'Option: '.$product['width'];
		if(trim($product['color']) != '')
			$options[] = 'Color: '.$product['color'];
			
		$tmp['[products]'] .= "<tr>
				<td>".$product['name']."</td>
				<td>".implode(', ', $options)."</td>
				<td align=\"right\">".price($product['order_price']-$product['order_discount'])."</td>
				<td align=\"right\">".$product['order_quantity']."</td>
				<td align=\"right\">".price(($product['order_price']-$product['order_discount'])*$product['order_quantity'])."</td>
			</tr>";
	$tmp['[products]'] .= '<tr>
					<td colspan="3" align="right">Subtotal</td>
					<td align="right">'.price($vars['order']['total']).'</td>
				</tr>
				<tr>
					<td colspan="3" align="right">Shipping</td>
					<td align="right">'.price($vars['order']['shipping']).'</td>
				</tr>
				<tr>
					<td colspan="3" align="right">Total</td>
					<td align="right">'.price($vars['order']['total']+$vars['order']['shipping']).'</td>
				</tr>
			</table>';
	}
	
	$replace = array();
	foreach($variables as $variable=>$unused)
		if(isset($tmp[$variable]))
			$replace[$variable] = $tmp[$variable];
		else
			$replace[$variable] = '';
			
	$html_content = str_replace(array_keys($replace), $replace, $cms_email['content']);
	
	$tmp['[products]'] = "Product					Price		Quantity	Subtotal\n\n";
	foreach($vars['products'] as $product)
		$tmp['[products]'] .= $product['name']."	".price($product['order_price']-$product['order_discount'])."	".$product['order_quantity']."	".price(($product['order_price']-$product['order_discount'])*$product['order_quantity'])."\n";;
	$tmp['[products]'] .= "Subtotal	".price($vars['order']['total'])."

Shipping	".price($vars['order']['shipping'])."

Total		".price($vars['order']['total']+$vars['order']['shipping']);
	
	$replace = array();
	foreach($variables as $variable=>$unused)
		if(isset($tmp[$variable]))
			$replace[$variable] = $tmp[$variable];
		else
			$replace[$variable] = '';
			
	$text_content = strip_tags(str_replace(array_keys($replace), $replace, $cms_email['content']));
	
	$subject=$cms_email['subject'];
	$embed[0]="images/email/logo.gif";
?>